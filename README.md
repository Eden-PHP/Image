![logo](http://eden.openovate.com/assets/images/cloud-social.png) Eden Image
====
[![Build Status](https://api.travis-ci.org/Eden-PHP/Image.svg)](https://travis-ci.org/Eden-PHP/Image)
====

 - [Install](#install)
 - [Introduction](#intro)
 - [API](#api)
    - [blur](#blur)
    - [brightness](#brightness)
    - [colorize](#colorize)
    - [contrast](#contrast)
    - [crop](#crop)
    - [edgedetect](#edgedetect)
    - [emboss](#emboss)
    - [gaussianBlur](#gaussianBlur)
    - [getDimensions](#getDimensions)
    - [getResource](#getResource)
    - [greyscale](#greyscale)
    - [invert](#invert)
    - [meanRemoval](#meanRemoval)
    - [negative](#negative)
    - [resize](#resize)
    - [rotate](#rotate)
    - [scale](#scale)
    - [setTransparency](#setTransparency)
    - [smooth](#smooth)
    - [save](#save)
 - [Contributing](#contributing)

====

<a name="install"></a>
## Install

`composer install eden/image`

====

<a name="intro"></a>
## Introduction

Instantiate image in this manner.

```
$image = eden('image', '/path/to/image.jpg');
```

Once you are done modifying the image you can save the image to a file or simply echo out the image object like below.

```
header('Content-Type: image/jpg');
echo $image;
```

====

<a name="api"></a>
## API

==== 

<a name="blur"></a>

### blur

Applies the selective blur filter. Blurs the image 

#### Usage

```
eden('image', '/path/to/image.jpg')->blur();
```

#### Parameters

Returns `Eden\Image\Index`

==== 

<a name="brightness"></a>

### brightness

Applies the brightness filter. Changes the brightness of the image. 

#### Usage

```
eden('image', '/path/to/image.jpg')->brightness(*number $level);
```

#### Parameters

 - `*number $level` - The level of brightness

Returns `Eden\Image\Index`

#### Example

```
eden('image', '/path/to/image.jpg')->brightness($level);
```

==== 

<a name="colorize"></a>

### colorize

Applies the colorize filter. Like greyscale except you can specify the color. 

#### Usage

```
eden('image', '/path/to/image.jpg')->colorize(*number $red, *number $blue, *number $green, number $alpha);
```

#### Parameters

 - `*number $red` - The 255 value of red to use
 - `*number $blue` - The 255 value of blue to use
 - `*number $green` - The 255 value of green to use
 - `number $alpha` - The level of alpha transparency

Returns `Eden\Image\Index`

#### Example

```
eden('image', '/path/to/image.jpg')->colorize($red, $blue, $green);
```

==== 

<a name="contrast"></a>

### contrast

Applies the contrast filter. Changes the contrast of the image. 

#### Usage

```
eden('image', '/path/to/image.jpg')->contrast(*number $level);
```

#### Parameters

 - `*number $level` - The level of contrast

Returns `Eden\Image\Index`

#### Example

```
eden('image', '/path/to/image.jpg')->contrast($level);
```

==== 

<a name="crop"></a>

### crop

Crops the image 

#### Usage

```
eden('image', '/path/to/image.jpg')->crop(int|null $width, int|null $height);
```

#### Parameters

 - `int|null $width` - The width; If null will use the original width
 - `int|null $height` - The height; If null will use the original height

Returns `Eden\Image\Index`

#### Example

```
eden('image', '/path/to/image.jpg')->crop();
```

==== 

<a name="edgedetect"></a>

### edgedetect

Applies the edgedetect filter. Uses edge detection to highlight the edges in the image. 

#### Usage

```
eden('image', '/path/to/image.jpg')->edgedetect();
```

#### Parameters

Returns `Eden\Image\Index`

==== 

<a name="emboss"></a>

### emboss

Applies the emboss filter. Embosses the image. 

#### Usage

```
eden('image', '/path/to/image.jpg')->emboss();
```

#### Parameters

Returns `Eden\Image\Index`

==== 

<a name="gaussianBlur"></a>

### gaussianBlur

Applies the gaussian blur filter. Blurs the image using the Gaussian method. 

#### Usage

```
eden('image', '/path/to/image.jpg')->gaussianBlur();
```

#### Parameters

Returns `Eden\Image\Index`

==== 

<a name="getDimensions"></a>

### getDimensions

Returns the size of the image 

#### Usage

```
eden('image', '/path/to/image.jpg')->getDimensions();
```

#### Parameters

Returns `array`

==== 

<a name="getResource"></a>

### getResource

Returns the resource for custom editing 

#### Usage

```
eden('image', '/path/to/image.jpg')->getResource();
```

#### Parameters

Returns `[RESOURCE]`

==== 

<a name="greyscale"></a>

### greyscale

Applies the greyscale filter. Converts the image into grayscale. 

#### Usage

```
eden('image', '/path/to/image.jpg')->greyscale();
```

#### Parameters

Returns `Eden\Image\Index`

==== 

<a name="invert"></a>

### invert

Inverts the image. 

#### Usage

```
eden('image', '/path/to/image.jpg')->invert(bool $vertical);
```

#### Parameters

 - `bool $vertical` - If true invert vertical; if false invert horizontal

Returns `Eden\Image\Index`

#### Example

```
eden('image', '/path/to/image.jpg')->invert();
```

==== 

<a name="meanRemoval"></a>

### meanRemoval

Applies the mean removal filter. Uses mean removal to achieve a "sketchy" effect. 

#### Usage

```
eden('image', '/path/to/image.jpg')->meanRemoval();
```

#### Parameters

Returns `Eden\Image\Index`

==== 

<a name="negative"></a>

### negative

Applies the greyscale filter. Reverses all colors of the image. 

#### Usage

```
eden('image', '/path/to/image.jpg')->negative();
```

#### Parameters

Returns `Eden\Image\Index`

==== 

<a name="resize"></a>

### resize

Resizes the image. This is a version of scale but keeping it's original aspect ratio 

#### Usage

```
eden('image', '/path/to/image.jpg')->resize(int|null $width, int|null $height);
```

#### Parameters

 - `int|null $width` - the width; if null will use the original width
 - `int|null $height` - the height; if null will use the original height

Returns `Eden\Image\Index`

#### Example

```
eden('image', '/path/to/image.jpg')->resize();
```

==== 

<a name="rotate"></a>

### rotate

Rotates the image. 

#### Usage

```
eden('image', '/path/to/image.jpg')->rotate(*int $degree, int $background);
```

#### Parameters

 - `*int $degree` - The degree to rotate by
 - `int $background` - Background color code

Returns `Eden\Image\Index`

#### Example

```
eden('image', '/path/to/image.jpg')->rotate(123);
```

==== 

<a name="scale"></a>

### scale

Scales the image. If width or height is set to null a width or height will be auto determined based on the aspect ratio 

#### Usage

```
eden('image', '/path/to/image.jpg')->scale(int|null $width, int|null $height);
```

#### Parameters

 - `int|null $width` - The width; if null will use the original width
 - `int|null $height` - The height; if null will use the original height

Returns `Eden\Image\Index`

#### Example

```
eden('image', '/path/to/image.jpg')->scale();
```

==== 

<a name="setTransparency"></a>

### setTransparency

Sets the background color to be transparent 

#### Usage

```
eden('image', '/path/to/image.jpg')->setTransparency();
```

#### Parameters

Returns `Eden\Image\Index`

==== 

<a name="smooth"></a>

### smooth

Applies the smooth filter. Makes the image smoother. 

#### Usage

```
eden('image', '/path/to/image.jpg')->smooth(*number $level);
```

#### Parameters

 - `*number $level` - The level of smoothness

Returns `Eden\Image\Index`

#### Example

```
eden('image', '/path/to/image.jpg')->smooth($level);
```

==== 

<a name="save"></a>

### save

Saves the image data to a file 

#### Usage

```
eden('image', '/path/to/image.jpg')->save(*string $path, string|null $type);
```

#### Parameters

 - `*string $path` - The path to save to
 - `string|null $type` - The render type

Returns `Eden\Image\Index`

#### Example

```
eden('image', '/path/to/image.jpg')->save('foo');
```

==== 

<a name="contributing"></a>
#Contributing to Eden

Contributions to *Eden* are following the Github work flow. Please read up before contributing.

##Setting up your machine with the Eden repository and your fork

1. Fork the repository
2. Fire up your local terminal create a new branch from the `v4` branch of your 
fork with a branch name describing what your changes are. 
 Possible branch name types:
    - bugfix
    - feature
    - improvement
3. Make your changes. Always make sure to sign-off (-s) on all commits made (git commit -s -m "Commit message")

##Making pull requests

1. Please ensure to run `phpunit` before making a pull request.
2. Push your code to your remote forked version.
3. Go back to your forked version on GitHub and submit a pull request.
4. An Eden developer will review your code and merge it in when it has been classified as suitable.
