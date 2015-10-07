[![Build Status](https://api.travis-ci.org/Eden-PHP/Image.png)](https://travis-ci.org/Eden-PHP/Image)
===
# Images

Images Manipulation in *Eden* takes the leg work from trying to figure it out on your own. There are two ways to load images in Eden; by the file name or the data itself.

> **Note:** It's not actually good practice to pass image data using a variable because of its mere size. There are some cases however, like getting image data from a web service where this cannot be avoided.

**Figure 1. Load by File or Data**

	$image = eden('image', '/path/to/image.jpg', 'jpg');
	$image = eden('image', $image_data, 'jpg', false);

Once the image is loaded, you can combine several methods to manipulate the image to your liking.

**Figure 2. Image Manipulation**

	$image->crop(300, 300);       // Crops an image
	$image->scale(300, 300);      // Scales an image
	$image->resize(300, 300);     // Scales an image while keeping aspect ration
	$image->rotate(90);           // Rotates image
	$image->invert();             // Invert horizontal
	$image->invert(true);         // Invert vertical
	$image->greyscale();                 
	$image->negative();           // inverses all the colors
	$image->brightness(4);                   
	$image->contrast(4);                 
	$image->colorize(0, 0, 255);  // colorize to blue (R, G, B)
	$image->edgedetect();         // highlight edges
	$image->emboss();                        
	$image->gaussianBlur();
	$image->blur();
	$image->meanRemoval();        // achieve a "sketchy" effect.
	$image->smooth(10);
	$image->setTransparency();    // set the transparent color

If the image is loaded by data, using `getDimensions()` will return the width and height of that loaded image. 

**Figure 3. Get the image dimensions**

	$image->getDimensions(); // get the width and height

Though *Eden's* image object solves for most general cases. There will be times you may need to add additional filters beyond a function call. For advanced manipulation you can get the GD2 resource like in `Figure 4`.

**Figure 4. Get the Resource**

	$image->getResource(); // get the GD resource for advanced editing

When your happy with your image you have the choices to save it to a file or echo to screen. `Figure 5` and `Figure 6` both show ways to get the final image. 

> **Note:** When echoing an image, make sure there is no other data outputted or the final output will look broken.

**Figure 5. Save to File**

	$image->save('/path/to/file.jpg', 'jpg'); // save image to file

**Figure 6. Simply Echo**

	header('Content-type: image/jpeg');
	echo $image; //prints the image data

====

#Contributing to Eden

##Setting up your machine with the Eden repository and your fork

1. Fork the main Eden repository (https://github.com/Eden-PHP/Image)
2. Fire up your local terminal and clone the *MAIN EDEN REPOSITORY* (git clone git://github.com/Eden-PHP/Image.git)
3. Add your *FORKED EDEN REPOSITORY* as a remote (git remote add fork git@github.com:*github_username*/Image.git)

##Making pull requests

1. Before anything, make sure to update the *MAIN EDEN REPOSITORY*. (git checkout master; git pull origin master)
2. If PHP Unit testing is included in this package please make sure to update it and run the test to ensure everything still works (phpunit)
3. Once updated with the latest code, create a new branch with a branch name describing what your changes are (git checkout -b bugfix/fix-twitter-auth)
    Possible types:
    - bugfix
    - feature
    - improvement
4. Make your code changes. Always make sure to sign-off (-s) on all commits made (git commit -s -m "Commit message")
5. Once you've committed all the code to this branch, push the branch to your *FORKED EDEN REPOSITORY* (git push fork bugfix/fix-twitter-auth)
6. Go back to your *FORKED EDEN REPOSITORY* on GitHub and submit a pull request.
7. An Eden developer will review your code and merge it in when it has been classified as suitable.
