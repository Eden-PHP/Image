<?php //-->
/*
 * This file is part of the Image package of the Eden PHP Library.
 * (c) 2013-2014 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE
 * distributed with this package.
 */

namespace Eden\Image;

/**
 * The base class for all classes wishing to integrate with Eden.
 * Extending this class will allow your methods to seemlessly be
 * overloaded and overrided as well as provide some basic class
 * loading patterns.
 *
 * @vendor Eden
 * @package image
 * @author Christian Blanquera cblanquera@openovate.com
 */
class Index extends Base
{
	const GD_NOT_INSTALLED = 'PHP GD Library is not installed.';
    const NOT_VALID_IMAGE_FILE = '%s is not a valid image file.';

    protected $resource = null;
    protected $width = 0;
    protected $height = 0;

    /**
     * Pre set the image data
     *
     * @param *string
     * @param string|null
     * @param bool
     * @param int
     * @return void
     */
    public function __construct($data, $type = null, $path = true, $quality = 75)
    {
        Argument::i()
			//argument 1 must be a string
            ->test(1, 'string')         
			//argument 2 must be a string or null
            ->test(2, 'string', 'null') 
			//argument 3 must be a boolean
            ->test(3, 'bool')           
			//argument 4 must be an integer
            ->test(4, 'int');           

        $this->type = $type;

        //some render functions allow you
        //to set the quality of the render
        $this->quality = $quality;

        //create the resource
        $this->resource = $this->createResource($data, $path);

        //set the initial with and height
        list($this->width, $this->height) = $this->getDimensions();
    }

    /**
     * Incase we forget lets destroy the image
     *
     * @return void
     */
    public function __destruct()
    {
        if($this->resource) {
            imagedestroy($this->resource);
        }
    }

    /**
     * Renders the image to raw data
     *
     * @return string
     */
    public function __toString()
    {
        #imagepng() - Output a PNG image to either the browser or a file
        #imagegif() - Output image to browser or file
        #imagewbmp() - Output image to browser or file
        #imagejpeg() - Output image to browser or file
        ob_start();
        switch($this->type) {
            case 'gif':
                imagegif($this->resource);
                break;
            case 'png':
                $quality = (100 - $this->quality) / 10;

                if($quality > 9) {
                    $quality = 9;
                }

                imagepng($this->resource, null, $quality);
                break;
            case 'bmp':
            case 'wbmp':
                imagewbmp($this->resource, null, $this->quality);
                break;
            case 'jpg':
            case 'jpeg':
            case 'pjpeg':
            default:
                imagejpeg($this->resource, null, $this->quality);
                break;

        }

        return ob_get_clean();
    }

    /**
     * Applies the selective blur filter. Blurs the image
     *
     * @return this
     */
    public function blur()
    {
        //apply filter
        imagefilter($this->resource, IMG_FILTER_SELECTIVE_BLUR);

        return $this;
    }

    /**
     * Applies the brightness filter. Changes the brightness of the image.
     *
     * @param *number level
     * @return this
     */
    public function brightness($level)
    {
        //Argument 1 must be a number
        Argument::i()->test(1, 'numeric');

        //apply filter
        imagefilter($this->resource, IMG_FILTER_BRIGHTNESS, $level);

        return $this;
    }

    /**
     * Applies the colorize filter. Like greyscale except you can specify the color.
     *
     * @param *number red
     * @param *number blue
     * @param *number green
     * @param number alpha
     * @return this
     */
    public function colorize($red, $blue, $green, $alpha = 0)
    {
        //argument test
        Argument::i()
			//Argument 1 must be a number
            ->test(1, 'numeric')  
			//Argument 2 must be a number
            ->test(2, 'numeric')  
			//Argument 3 must be a number
            ->test(3, 'numeric') 
			//Argument 4 must be a number
            ->test(4, 'numeric'); 

        //apply filter
        imagefilter($this->resource, IMG_FILTER_COLORIZE, $red, $blue, $green, $alpha);

        return $this;
    }

    /**
     * Applies the contrast filter. Changes the contrast of the image.
     *
     * @param *number level
     * @return this
     */
    public function contrast($level)
    {
        //Argument 1 must be a number
        Argument::i()->test(1, 'numeric');

        //apply filter
        imagefilter($this->resource, IMG_FILTER_CONTRAST, $level);

        return $this;
    }

    /**
     * Crops the image
     *
     * @param int|null the width; if null will use the original width
     * @param int|null the height; if null will use the original height
     * @return this
     */
    public function crop($width = null, $height = null)
    {
        //argument test
        Argument::i()
			//Argument 1 must be a number or null
            ->test(1, 'numeric', 'null')	
			//Argument 2 must be a number or null
            ->test(2, 'numeric', 'null');	

        //get the source width and height
        $orgWidth = imagesx($this->resource);
        $orgHeight = imagesy($this->resource);

        //set the width if none is defined
        if(is_null($width)) {
            $width = $orgWidth;
        }

        //set the height if none is defined
        if(is_null($height)) {
            $height = $orgHeight;
        }

        //if the width and height are the same as the originals
        if($width == $orgWidth && $height == $orgHeight) {
            //there's no need to process
            return $this;
        }

        //if we are here then we do need to crop
        //create the new resource with the width and height
        $crop = imagecreatetruecolor($width, $height);

        //set some defaults
        $xPosition = 0;
        $yPosition = 0;

        //if the width is greater than the original width
        //or if the height is greater than the original height
        if($width > $orgWidth || $height > $orgHeight) {
            //save the destination width and height
            //because they will change here
            $newWidth = $width;
            $newHeight = $height;

            //if the desired height is larger than the desired width
            if($height > $width) {
                //and adjust the height instead
                $height = $this->getHeightAspectRatio($orgWidth, $orgHeight, $width);
                //if the aspect height is bigger than the desired height
                if($newHeight > $height) {
                    //set it back to the desired height
                    $height = $newHeight;
                    //and adjust the width instead
                    $width = $this->getWidthAspectRatio($orgWidth, $orgHeight, $height);
                    //now because of the way GD renders we need to find the ratio of desired
                    //height if it was brought down to the original height
                    $rWidth = $this->getWidthAspectRatio($newWidth, $newHeight, $orgHeight);
                    //set the x Position of the source to the center of the
                    //original width image width minus half the rWidth width
                    $xPosition = ($orgWidth / 2) - ($rWidth / 2);
                } else {
                    //now because of the way GD renders we need to find the ratio of desired
                    //height if it was brought down to the original height
                    $rHeight = $this->getHeightAspectRatio($newWidth, $newHeight, $orgWidth);
                    //set the y Position of the source to the center of the
                    //new sized image height minus half the desired height
                    $yPosition = ($orgHeight / 2) - ($rHeight / 2) ;
                }
            //if the desired height is smaller than the desired width
            } else {
                //get the width aspect ratio
                $width = $this->getWidthAspectRatio($orgWidth, $orgHeight, $height);
                //if the aspect height is bigger than the desired height
                if($newWidth > $width) {
                    //set it back to the desired height
                    $width = $newWidth;
                    //and adjust the width instead
                    $height = $this->getHeightAspectRatio($orgWidth, $orgHeight, $width);
                    //now because of the way GD renders we need to find the ratio of desired
                    //height if it was brought down to the original height
                    $rHeight = $this->getHeightAspectRatio($newWidth, $newHeight, $orgWidth);
                    //set the y Position of the source to the center of the
                    //new sized image height minus half the desired height
                    $yPosition = ($orgHeight / 2) - ($rHeight / 2) ;
                } else {
                    //now because of the way GD renders we need to find the ratio of desired
                    //height if it was brought down to the original height
                    $rWidth = $this->getWidthAspectRatio($newWidth, $newHeight, $orgHeight);
                    //set the x Position of the source to the center of the
                    //original width image width minus half the rWidth width
                    $xPosition = ($orgWidth / 2) - ($rWidth / 2);
                }
            }
        } else {
            //if the width is less than the original width
            if($width < $orgWidth) {
                //set the x Position of the source to the center of the
                //original image width minus half the desired width
                $xPosition = ($orgWidth / 2) - ($width / 2);
                //set the destination width to be the original width
                $width = $orgWidth;
            }

            //if the height is less than the original height
            if($height < $orgHeight) {
                //set the y Position of the source to the center of the
                //original image height minus half the desired height
                $yPosition = ($orgHeight / 2) - ($height / 2);
                //set the destination height to be the original height
                $height = $orgHeight;
            }
        }

        //render the image
        imagecopyresampled($crop, $this->resource, 0, 0, $xPosition, $yPosition, $width, $height, $orgWidth, $orgHeight);

        //destroy the original resource
        imagedestroy($this->resource);

        //assign the new resource
        $this->resource = $crop;

        return $this;
    }

    /**
     * Applies the edgedetect filter. Uses edge detection to highlight the edges in the image.
     *
     * @return this
     */
    public function edgedetect()
    {
        //apply filter
        imagefilter($this->resource, IMG_FILTER_EDGEDETECT);

        return $this;
    }

    /**
     * Applies the emboss filter. Embosses the image.
     *
     * @return this
     */
    public function emboss()
    {
        //apply filter
        imagefilter($this->resource, IMG_FILTER_EMBOSS);

        return $this;
    }

    /**
     * Applies the gaussian blur filter. Blurs the image using the Gaussian method.
     *
     * @return this
     */
    public function gaussianBlur()
    {
        //apply filter
        imagefilter($this->resource, IMG_FILTER_GAUSSIAN_BLUR);

        return $this;
    }

    /**
     * Returns the size of the image
     *
     * @return array
     */
    public function getDimensions()
    {
        return array(imagesx($this->resource), imagesy($this->resource));
    }

    /**
     * Returns the resource for custom editing
     *
     * @return [RESOURCE]
     */
    public function getResource()
    {
        return $this->resource;
    }

    /**
     * Applies the greyscale filter. Converts the image into grayscale.
     *
     * @return this
     */
    public function greyscale()
    {
        //apply filter
        imagefilter($this->resource, IMG_FILTER_GRAYSCALE);

        return $this;
    }

    /**
     * Inverts the image.
     *
     * @param bool if true invert vertical; if false invert horizontal
     * @return this
     */
    public function invert($vertical = false)
    {
        //Argument 1 must be a boolean
        Argument::i()->test(1, 'bool');

        //get the source width and height
        $orgWidth = imagesx($this->resource);
        $orgHeight = imagesy($this->resource);

        $invert = imagecreatetruecolor($orgWidth, $orgHeight);

        if($vertical) {
            imagecopyresampled(
                $invert,
                $this->resource,
                0,
                0,
                0,
                $orgHeight-1,
                $orgWidth,
                $orgHeight,
                $orgWidth,
                0-$orgHeight);
        } else {
            imagecopyresampled(
                $invert,
                $this->resource,
                0,
                0,
                $orgWidth-1,
                0,
                $orgWidth,
                $orgHeight,
                0-$orgWidth,
                $orgHeight);
        }

        //destroy the original resource
        imagedestroy($this->resource);

        //assign the new resource
        $this->resource = $invert;

        return $this;
    }

    /**
     * Applies the mean removal filter. Uses mean removal to achieve a "sketchy" effect.
     *
     * @return this
     */
    public function meanRemoval()
    {
        //apply filter
        imagefilter($this->resource, IMG_FILTER_MEAN_REMOVAL);

        return $this;
    }

    /**
     * Applies the greyscale filter. Reverses all colors of the image.
     *
     * @return this
     */
    public function negative()
    {
        //apply filter
        imagefilter($this->resource, IMG_FILTER_NEGATE);

        return $this;
    }

    /**
     * Resizes the image. This is a version of
     * scale but keeping it's original aspect ratio
     *
     * @param int|null the width; if null will use the original width
     * @param int|null the height; if null will use the original height
     * @return this
     */
    public function resize($width = null, $height = null)
    {
        //argument test
        Argument::i()
			//Argument 1 must be a number or null
            ->test(1, 'numeric', 'null')  
			//Argument 2 must be a number or null
            ->test(2, 'numeric', 'null'); 

        //get the source width and height
        $orgWidth = imagesx($this->resource);
        $orgHeight = imagesy($this->resource);

        //set the width if none is defined
        if(is_null($width)) {
            $width = $orgWidth;
        }

        //set the height if none is defined
        if(is_null($height)) {
            $height = $orgHeight;
        }

        //if the width and height are the same as the originals
        if($width == $orgWidth && $height == $orgHeight) {
            //there's no need to process
            return $this;
        }

        $newWidth = $width;
        $newHeight = $height;

        //if the desired height is larger than the desired width
        if($height < $width) {
            //get the width aspect ratio
            $width = $this->getWidthAspectRatio($orgWidth, $orgHeight, $height);
            //if the aspect width is bigger than the desired width
            if($newWidth < $width) {
                //set it back to the desired width
                $width = $newWidth;
                //and adjust the height instead
                $height = $this->getHeightAspectRatio($orgWidth, $orgHeight, $width);
            }
        //if the desired height is smaller than the desired width
        } else {
            //get the width aspect ratio
            $height = $this->getHeightAspectRatio($orgWidth, $orgHeight, $width);
            //if the aspect height is bigger than the desired height
            if($newHeight < $height) {
                //set it back to the desired height
                $height = $newHeight;
                //and adjust the width instead
                $width = $this->getWidthAspectRatio($orgWidth, $orgHeight, $height);
            }
        }

        return $this->scale($width, $height);
    }

    /**
     * Rotates the image.
     *
     * @param *int the degree to rotate by
     * @param int background color code
     * @return this
     */
    public function rotate($degree, $background = 0)
    {
        //argument test
        Argument::i()
			//Argument 1 must be a number
            ->test(1, 'numeric')	
			//Argument 2 must be a number
            ->test(2, 'numeric');	

        //rotate the image
        $rotate = imagerotate($this->resource, $degree, $background);

        //destroy the original resource
        imagedestroy($this->resource);

        //assign the new resource
        $this->resource = $rotate;

        return $this;
    }

    /**
     * Scales the image. If width or height is set
     * to null a width or height will be auto determined based on the
     * aspect ratio
     *
     * @param int|null the width; if null will use the original width
     * @param int|null the height; if null will use the original height
     * @return this
     */
    public function scale($width = null, $height = null)
    {
        //argument test
        Argument::i()
			//Argument 1 must be a number or null
            ->test(1, 'numeric', 'null')	
			//Argument 2 must be a number or null
            ->test(2, 'numeric', 'null');	

        //get the source width and height
        $orgWidth = imagesx($this->resource);
        $orgHeight = imagesy($this->resource);

        //set the width if none is defined
        if(is_null($width)) {
            $width = $orgWidth;
        }

        //set the height if none is defined
        if(is_null($height)) {
            $height = $orgHeight;
        }

        //if the width and height are the same as the originals
        if($width == $orgWidth && $height == $orgHeight) {
            //there's no need to process
            return $this;
        }

        //if we are here then we do need to crop
        //create the new resource with the width and height
        $scale = imagecreatetruecolor($width, $height);

        //render the image
        imagecopyresampled($scale, $this->resource, 0, 0, 0, 0, $width, $height, $orgWidth, $orgHeight);

        //destroy the original resource
        imagedestroy($this->resource);

        //assign the new resource
        $this->resource = $scale;

        return $this;
    }

    /**
     * Sets the background color to be transparent
     *
     * @return this
     */
    public function setTransparency()
    {
        imagealphablending( $this->resource, false );
        imagesavealpha( $this->resource, true );

        return $this;
    }

    /**
     * Applies the smooth filter. Makes the image smoother.
     *
     * @param *number level
     * @return this
     */
    public function smooth($level)
    {
        //Argument 1 must be a number
        Argument::i()->test(1, 'numeric');

        //apply filter
        imagefilter($this->resource, IMG_FILTER_SMOOTH, $level);

        return $this;
    }

    /**
     * Saves the image data to a file
     *
     * @param *string the path to save to
     * @param string|null the render type
     * @return this
     */
    public function save($path, $type = null)
    {
        #imagepng() - Output a PNG image to either the browser or a file
        #imagegif() - Output image to browser or file
        #imagewbmp() - Output image to browser or file
        #imagejpeg() - Output image to browser or file
        
		if(!$type) {
            $type = $this->type;
        }

        switch($type) {
            case 'gif':
                imagegif($this->resource, $path);
                break;
            case 'png':
                $quality = (100 - $this->quality) / 10;

                if($quality > 9) {
                    $quality = 9;
                }

                imagepng($this->resource, $path, $quality);
                break;
            case 'bmp':
            case 'wbmp':
                imagewbmp($this->resource, $path, $this->quality);
                break;
            case 'jpg':
            case 'jpeg':
            case 'pjpeg':
            default:
                imagejpeg($this->resource, $path, $this->quality);
                break;

        }

        return $this;
    }

    /**
     * Determines the preserved height given the original dimensions and the width
     *
     * @param *number
     * @param *number
     * @param *number
     * @return number
     */
    protected function getHeightAspectRatio($sourceWidth, $sourceHeight, $destinationWidth)
    {
        $ratio = $destinationWidth / $sourceWidth;
        return  $sourceHeight * $ratio;
    }

    /**
     * Returns the GD image resource
     *
     * @param *string
     * @param *string
     * @return [RESOURCE]
     */
    protected function createResource($data, $path)
    {
        //if the GD Library is not installed
        if(!function_exists('gd_info')) {
            //throw error
            Exception::i(self::GD_NOT_INSTALLED)->trigger();
        }

        # imagecreatefromgd — Create a new image from GD file or URL
        # imagecreatefromgif — Create a new image from file or URL
        # imagecreatefromjpeg — Create a new image from file or URL
        # imagecreatefrompng — Create a new image from file or URL
        # imagecreatefromstring — Create a new image from the image stream in the string
        # imagecreatefromwbmp — Create a new image from file or URL
        # imagecreatefromxbm — Create a new image from file or URL
        # imagecreatefromxpm — Create a new image from file or URL

        $resource = false;

        if(!$path) {
            return imagecreatefromstring($data);
        }

        //depending on the extension lets load
        //the file using the right GD loader
        switch($this->type) {
            case 'gd':
                $resource = imagecreatefromgd($data);
                break;
            case 'gif':
                $resource = imagecreatefromgif($data);
                break;
            case 'jpg':
            case 'jpeg':
            case 'pjpeg':
                $resource = imagecreatefromjpeg($data);
                break;
            case 'png':
                $resource = imagecreatefrompng($data);
                break;
            case 'bmp':
            case 'wbmp':
                $resource = imagecreatefromwbmp($data);
                break;
            case 'xbm':
                $resource = imagecreatefromxbm($data);
                break;
            case 'xpm':
                $resource = imagecreatefromxpm($data);
                break;
        }

        //if there is no resource still
        if(!$resource) {
            //throw error
            Exception::i()
                ->setMessage(self::NOT_VALID_IMAGE_FILE)
                ->addVariable($path);
        }

        return $resource;
    }

    /**
     * Determines the preserved width given the original dimensions and the height
     *
     * @param *number
     * @param *number
     * @param *number
     * @return number
     */
    protected function getWidthAspectRatio($sourceWidth, $sourceHeight, $destinationHeight)
    {
        $ratio = $destinationHeight / $sourceHeight;
        return  $sourceWidth * $ratio;
    }

}