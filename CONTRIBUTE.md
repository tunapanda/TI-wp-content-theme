# Creating Swagpaths

A **swagpath** is a series of learning resources and/or activities (called **swagifacts**) organized in an ordered path to enable the player to gather skills and knowledge, while generating swag points. A swagpath can be visualized in a **swagmap**.

[INSERT SCREENSHOT OF SWAGMAP]

To earn the swag points for the "Creating a Usable Swagpath on http://content.tunapanda.org" skill, a player needs to complete the following swagpath:
 1. Secure an author account.
 2. Upload swagifacts or create them within the site.
 3. Design the swagifacts into a swagpath.
 4. Create a description for the swagpath.

## Step 1: Securing an author account

To secure an author account please send an email to wp-authors@tunapanda.org with a brief description of why you would like an account and what swagifacts and/or swagpaths you are interested in creating. We will respond within 48 business hours.

In the meantime, you can begin creating H5P swagifacts by either [test driving H5P](https://h5p.org/testdrive-h5p) or [installing H5P on your machine or website](https://h5p.org/installation). The H5P items you create now can be saved and uploaded to http://content.tunapanda.org later. Full instructions, including Wordpress setup, can be found [here](https://h5p.org/documentation/setup/wordpress)

## Step 2: Uploading or creating H5P swagifacts

_Note: this step assumes you know how to author H5P items. You can [learn how to use H5P here](https://h5p.org/documentation/for-authors)._

First, head over to http://content.tunapanda.org/wp-admin and login with your Author Account (which you acquired in Step 1).

![Wordpress Login](https://github.com/tunapanda/TI-wp-content-theme/blob/master/meta/contribute/logincontentwpadmin.png)

Next, hover over "H5P Content" on the left hand side and then click "Add New".

![Click on H5P](https://github.com/tunapanda/TI-wp-content-theme/blob/master/meta/contribute/clickh5psmall.png)

### Uploading pre-created H5P swagifacts

1. Select "Upload" on the right hand side.

 ![Upload H5P](https://github.com/tunapanda/TI-wp-content-theme/blob/master/meta/contribute/uploadh5p.png)

2. Add a Title for your swagifact.
3. Click "Choose File" and select the H5P swagifact you would like to upload.
4. Click the blue "Create" button.
5. Click "Add New" on the left side again and repeat steps 1-4 as needed.

 ![Add New](https://github.com/tunapanda/TI-wp-content-theme/blob/master/meta/contribute/addnewagain.png)

### Creating H5P swagifacts 

1. Select "Create" on the right hand side.
2. Add a Title for your swagifact.

 ![Upload H5P](https://github.com/tunapanda/TI-wp-content-theme/blob/master/meta/contribute/createh5p.png)

3. Select an H5P swagifact type, for example "[Course Presentation](https://h5p.org/tutorial-course-presentation)". (There are tutorials for other H5P types available [here](https://h5p.org/documentation/for-authors/tutorials)).

 ![Choose a Type](https://github.com/tunapanda/TI-wp-content-theme/blob/master/meta/contribute/h5ptype.png)

4. Build your swagifact.
5. Click the blue "Create" button.

 ![Create a Presentation](https://github.com/tunapanda/TI-wp-content-theme/blob/master/meta/contribute/createpresentation.png)

6. Click "Add New" on the left side again and repeat steps 1-5 as needed.

 ![Add New](https://github.com/tunapanda/TI-wp-content-theme/blob/master/meta/contribute/addnewagain.png)

## Step 3: Designing the swagifacts into a swagpath

A swagpath is made by creating a new Page in Wordpress, using the [course] and [h5p-course-item] shortcodes to create an ordered list of H5P swagifacts, and placing the swagpath in the appropriate part of the curriculum. Creating swagpaths requires that the the XXXXXX plugin be installed on your Wordpress instance.

1. Select "Pages" -> "Add New" from the left hand menu.

 ![New Page](https://github.com/tunapanda/TI-wp-content-theme/blob/master/meta/contribute/newpage.png)

2. Give the page a title.

 ![No Title](https://github.com/tunapanda/TI-wp-content-theme/blob/master/meta/contribute/notitle.png)

3. Under "Page Attributes" on the right hand side, find the "Parent" dropdown menu.

 ![No Parent](https://github.com/tunapanda/TI-wp-content-theme/blob/master/meta/contribute/noparent.png)

4. Select the appropriate Parent for your swagpath.

 ![Select Parent](https://github.com/tunapanda/TI-wp-content-theme/blob/master/meta/contribute/selectingparent.png)

 ![Parent Selected](https://github.com/tunapanda/TI-wp-content-theme/blob/master/meta/contribute/parentselected.png)

5. Insert the [course] and [/course] shortcodes into the page body.

 ![Course Shortcode](https://github.com/tunapanda/TI-wp-content-theme/blob/master/meta/contribute/courseshortcode.png)

6. (Optional) If you click the blue "Publish" button and navigate to learning.tunapanda.org, you will find your course listed without a description.

 ![Empty Course](https://github.com/tunapanda/TI-wp-content-theme/blob/master/meta/contribute/emptycourse.png)

7. Back in Wordpress: click between the [content] and [/content] shortcodes, select the "Add H5P" button, find the swagifact you wish to add to your swagpath, and click the "Insert" button.

 ![Insert H5P](https://github.com/tunapanda/TI-wp-content-theme/blob/master/meta/contribute/inserth5p.png)
 ![H5P ID](https://github.com/tunapanda/TI-wp-content-theme/blob/master/meta/contribute/h5pid.png)

8. Replace "h5p" with "h5p-course-item"
 
 ![H5P Single Item](https://github.com/tunapanda/TI-wp-content-theme/blob/master/meta/contribute/h5pcourseitem.png)

9. Repeat Steps 7-8 as necessary.

 ![H5P Course Items](https://github.com/tunapanda/TI-wp-content-theme/blob/master/meta/contribute/h5pcourseitems.png)

10. Click the "Publish" button and check out your swagpath on learning.tunapanda.org!

## Step 4: Create a description for the swagpath

To create a description for you swagpath you first need to enable the excerpt and custom field toolbars in Wordpress. We'll show you how now.

1. Click on "Screen Options" in the upper right.

 ![Click Screen Options](https://github.com/tunapanda/TI-wp-content-theme/blob/master/meta/contribute/clickscreenoptions.png)

 ![No Excerpt](https://github.com/tunapanda/TI-wp-content-theme/blob/master/meta/contribute/noexcerpt.png)

2. Select the "Excerpt" box.

 ![Select Excerpt](https://github.com/tunapanda/TI-wp-content-theme/blob/master/meta/contribute/selectexcerpt.png)

3. Scroll down to see the "Excerpt" widget.

 ![Excerpt](https://github.com/tunapanda/TI-wp-content-theme/blob/master/meta/contribute/excerptwidget.png)

4. Fill in the description for your swagpath.

 ![Excerpt Filled](https://github.com/tunapanda/TI-wp-content-theme/blob/master/meta/contribute/excerptfilled.png)

5. Click the blue "Publish" button on the right hand side.
6. Go to [learning.tunapanda.org](content.tunapanda.org) to see your course description.

 ![Swagpath Description](https://github.com/tunapanda/TI-wp-content-theme/blob/master/meta/contribute/excerptfilled/swagpathdescription.png)
