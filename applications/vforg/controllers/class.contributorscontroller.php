<?php
/**
 *
 *
 * @copyright 2009-2016 Vanilla Forums Inc.
 * @license http://www.opensource.org/licenses/gpl-2.0.php GNU GPL v2
 * @package VFOrg
 */

/**
 * Class ContributorsController
 */
class ContributorsController extends VFOrgController {

    /** @var array  */
    public $Uses = array('Form');

    /**
     * Show the contributor agreement form & workflow for signing.
     */
    public function index() {
        if (!Gdn::session()->isValid()) {
            $this->View = 'signin';
        } else {
            if ($this->Form->authenticatedPostBack() && $this->Form->getFormValue('Agree', '') == '1') {
                Gdn::sql()->update('User')
                    ->set('DateContributorAgreement', Gdn_Format::toDateTime(), true, false)
                    ->where('UserID', Gdn::session()->UserID)
                    ->put();
                $this->View = 'done';
            }
        }
        $this->render();
    }

    /**
     * Get list of signed contributors.
     */
    public function signed() {
        $this->UserData = Gdn::sql()
            ->select()
            ->from('User')
            ->where('DateContributorAgreement <>', '')
            ->orderBy('DateContributorAgreement', 'asc')
            ->get();

        $this->render();
    }
    
    /**
     * Endpoint for GitHub webhook for pull requests
     */
    public function pullRequest() {
        $payload = file_get_contents('php://input');
        if(!$this->verifySignature($payload)) {
          $this->renderData(['hookReceived' => false, 'error' => 'Invalid Signature']);
          return;
        }

        $data = json_decode($payload);
        $action = val('action', $data);
        switch($action) {
        case 'opened':
            $this->pullRequestOpened($data);
            break;
        default:
            $this->renderData(['hookReceived' => true]);
            break;
        }
    }
    
    /**
     * Verify an untamperered payload by calculating the signature and comparing
     * against the received header.
     * 
     * @param string $payload received by a GitHub webhook
     * @return bool
     */
    private function verifySignature($payload) {
        $secret = c('VForg.GitHub.PullRequestSecret');
        $expected = Gdn::request()->getValue('HTTP_X_HUB_SIGNATURE');
        $calculated = 'sha1=' . hash_hmac('sha1', $payload , $secret);
        return compareHashDigest($expected, $calculated);
    }
    
    /**
     * Called when a pull_request webhook is received with the 'opened' action
     * 
     * @param object $data
     */
    private function pullRequestOpened($data) {
        $gitHubName = valr('pull_request.user.login', $data);

        $userModel = new UserModel();
        $user = $userModel->getByUsername($gitHubName);

        $signed = !!val('DateContributorAgreement', $user);

        $this->commentOnSignedStatus($data, $signed, $gitHubName);

        $this->renderData(['hookReceived' => true, 'claSigned' => $signed]);
    }
  
    /**
     * Post a comment on the issue with the CLA status of the PR owner
     *  
     * @param object $data
     * @param bool $alreadySigned
     * @param string $name
     */
    private function commentOnSignedStatus($data, $alreadySigned, $name) {
        $body = '';
        if($alreadySigned) {
            $body .= sprintf(t("**%s** appears to have already signed the contributor's agreement."), $name);
        }
        else {
            $body .= sprintf(t("Can you sign our contributor's agreement @%s? http://vanillaforums.org/contributors"), $name);
        }

        $issue = valr('pull_request.number', $data);
        $repoOwner = valr('repository.owner.login', $data);
        $repoName = valr('repository.name', $data);

        if($issue && $repoOwner && $repoName) {
            $client = new Garden\Http\HttpClient('https://api.github.com');
            $client->setDefaultHeader('Content-Type', 'application/json');
            $client->setDefaultHeader('Authorization', 'token ' . c('VForg.GitHub.BotOAuthToken'));
            $client->post("/repos/$repoOwner/$repoName/issues/$issue/comments", ['body' => $body]);
        }
    }
}