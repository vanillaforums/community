<?php if (!defined('APPLICATION')) exit(); ?>
<div class="Wrapper FeaturesWrapper">
   <div id="Features" class="Center">
      <div class="Wrap">
         <h2>Vanilla Forums Features</h2>
         <p>The best way to manage feedback, spark discussion, and make customers smile. Hands down.</p>
      </div>
      <div class="FeatureSections">
         <div>
            <h4><i class="Sprite SpriteWand"></i> Simple</h4>
            <p class="About">
               You don't need an IT department to set up or
               manage your community. You'll be engaging
               with your customers in minutes.
            </p>
            <p>
               <?php echo Anchor('Learn More <i class="Sprite SpriteRarr SpriteRarrDown"><span>&rarr;</span></i>', 'features/#simple', 'BlueButton'); ?>
            </p>
         </div>
         <div>
            <h4><i class="Sprite SpriteColors"></i> Customizable</h4>
            <p class="About">
               Vanilla Forums give you <strong>total control</strong> over the features
               and appearance of your online community.
            </p>
            <p>
               <?php echo Anchor('Learn More <i class="Sprite SpriteRarr SpriteRarrDown"><span>&rarr;</span></i>', 'features/#customizable', 'BlueButton'); ?>
            </p>
         </div>
         <div>
            <h4><i class="Sprite SpriteBars"></i> Versatile</h4>
            <p class="About">
               Vanilla Forums offers modern features that give you even more
               functionality and control of your discussion forum.
            </p>
            <p>
               <?php echo Anchor('Learn More <i class="Sprite SpriteRarr SpriteRarrDown"><span>&rarr;</span></i>', 'features/#versatile', 'BlueButton'); ?>
            </p>
         </div>
      </div>

      <div id="simple" class="SubHead">
         <h2>Vanilla Forums are Simple to Use</h2>
         <p class="SubSubHead">No IT department necessary. Your mom could do this.</p>

         <div class="FeaturePage Simple">
            <div class="Feature">
               <div>
                  <h4><i class="Sprite SpriteWand"></i> Simple: Clean, Friendly Design</h4>
                  <ol>
                     <li>Easy-to-read user interface.</li>
                     <li>Users are clearly identified by self-chosen photos & usernames.</li>
                     <li>Vanilla Forums remember what you've read, and bring you right where you left off upon return.</li>
                     <li>Easy, in-line editing & deleting permissions for administrators.</li>
                     <li>User comments allow for live previewing, drafting, and auto-saving.</li>
         <!--             <li>Users can attach files to comments (Not available in free plan).</li> -->
                  </ol>
                  <?php echo Img('/applications/vforg/design/images/screen-features-discussion.png', array('class' => 'Screenshot', 'alt' => 'Simple - Discussion')); ?>
               </div>
            </div>
<!--   
            <div class="Feature">
               <div>
                  <h4><i class="Sprite SpriteWand"></i> Simple: Administrative Dashboard</h4>
                  <ol>
                     <li>Visit your Vanilla Forum by clicking the Visit Site link.</li>
                     <li>The My Account link allows you to upgrade or downgrade from your current plan at any time.</li>
                     <li>Stay on top of important notifications in your community from within your dashbaord.</li>
                     <li>Quickly find any administration section: Dashboard, Appearance, Users, Forum, and others depending on your plan.</li>
                     <li>Track & review activity in your community.</li>
                  </ol>
                  <?php echo Img('/applications/vforg/design/images/screen-features-dashboard.png', array('class' => 'Screenshot', 'alt' => 'Simple - Dashboard')); ?>
               </div>
            </div>
-->
         </div>
      </div>
      <div id="customizable" class="SubHead">
         <h2>Vanilla Forums are Customizable</h2>
         <p class="SubSubHead">Total control to appear just like your site.</p>

         <div class="FeaturePage Customizable">
            <div class="Feature">
               <div>
                  <h4><i class="Sprite SpriteColors"></i> Customizable: Quick &amp; Easy Ready-made Themes</h4>
                  <ol>
                     <li>Review your currently chosen theme.</li>
                     <li>New themes added regularly.</li>
                     <li>Preview and apply themes with a single button-click.</li>
                  </ol>
                  <?php echo Img('/applications/vforg/design/images/screen-features-themes.png', array('class' => 'Screenshot', 'alt' => 'Customizable - Themes')); ?>
               </div>
            </div>
   
         </div>
      </div>

      <div id="versatile" class="SubHead">
         <h2>Vanilla Forums are Versatile</h2>
         <p class="SubSubHead">Modern features of the web 3.0 movement are at your fingertips.</p>

         <div class="FeaturePage Versatile">
            <div class="Feature FeaturePanel">
               <div>
                  <h4><i class="Sprite SpriteBars"></i> Versatile: Killer Add-ons</h4>

                  <span class="Description">
                     <?php echo Img('/applications/vforg/design/images/screen-features-vanillaconnect.png', array('class' => 'Screenshot', 'alt' => 'Versatile - Vanilla Connect')); ?>
                     <strong>Vanilla Connect</strong>
                     <p>Allow your existing users to sign into your community forum quickly &amp; easily.</p>
                  </span>
               </div>
            </div>

            <div class="Feature FeaturePanel">
               <div>
                  <h4><i class="Sprite SpriteBars"></i> Versatile: User & Role Management</h4>
                  <span class="Description">
                     <?php echo Img('/applications/vforg/design/images/screen-features-role.png', array('class' => 'Screenshot', 'alt' => 'Versatile - Permissions')); ?>
                     <strong>Permissions</strong>
                     <p>Fine-grained control over who gets to do what.</p>
                  </span>
                  
                  <span class="Description">
                     <?php echo Img('/applications/vforg/design/images/screen-features-roles.png', array('class' => 'Screenshot', 'alt' => 'Versatile - Roles and Permissions')); ?>
                     <strong>Roles & Permissions</strong>
                     <p>Quickly &amp; easily create, manage, and organize roles.</p>
                  </span>
   
                  <span class="Description">
                     <?php echo Img('/applications/vforg/design/images/screen-features-user.png', array('class' => 'Screenshot', 'alt' => 'Versatile - Multi-role Users')); ?>
                     <strong>Multi-Role Users</strong>
                     <p>Users can be assigned to many roles.</p>
                  </span>
               </div>
            </div>

            <div class="Feature FeaturePanel">
               <div>
                  <h4><i class="Sprite SpriteBars"></i> Versatile: Extended Category Management</h4>
                  <span class="Description">
                     <?php echo Img('/applications/vforg/design/images/screen-features-categories.png', array('class' => 'Screenshot', 'alt' => 'Versatile - Nested Categories')); ?>
                     <strong>Nested Categories</strong>
                     <p>Choose to have no categories, or full-on nested categories.</p>
                  </span>
   
                  <span class="Description">
                     <?php echo Img('/applications/vforg/design/images/screen-features-category.png', array('class' => 'Screenshot', 'alt' => 'Versatile - Category Permissions')); ?>
                     <strong>Category Permissions</strong>
                     <p>You have total control over who gets to see particular discussion categories.</p>
                  </span>
               </div>
            </div>
   
         </div>
      </div>
   </div>
</div>