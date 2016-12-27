<?php

namespace Timmy;

use Timber;
use Twig_Environment;
use Twig_SimpleFilter;

class Timmy {
	public function __construct() {
		if ( class_exists( 'Timber\ImageHelper' ) ) {
			$this->init();
		}
	}

	public function init() {
		// Wait for theme to initialize to make sure that we can access all image sizes
		add_action( 'after_setup_theme', array( $this, 'after_setup_theme' ) );

		// Add filters to integrate Timmy into Timber and Twig
		add_filter( 'timber_context', array( $this, 'filter_timber_context' ) );
		add_filter( 'get_twig', array( $this, 'filter_get_twig' ) );
	}

	public function after_setup_theme() {
		if ( ! function_exists( 'get_image_sizes' ) ) {
			return;
		}

		// Add filters to make TimberImages work with normal WordPress image functionality
		add_filter( 'image_downsize', array( $this, 'filter_image_downsize' ), 10, 3 );
		add_filter( 'image_size_names_choose', array( $this, 'filter_image_size_names_choose' ), 10 );
		add_filter( 'intermediate_image_sizes', array( $this, 'filter_intermediate_image_sizes' ) );
		add_filter( 'intermediate_image_sizes_advanced', array( $this, 'filter_intermediate_image_sizes_advanced' ) );
		add_filter( 'wp_generate_attachment_metadata', array( $this, 'filter_wp_generate_attachment_metadata' ), 10, 2 );

		// Set global $_wp_additional_image_sizes
		$this->set_wp_additional_image_sizes();

		/**
		 * Third party filters
		 *
		 * - Make image sizes selectable in ACF
		 */
		add_filter( 'acf/get_image_sizes', array( $this, 'filter_acf_get_image_sizes' ) );
	}

	/**
	 * Add functions to Timber context
	 *
	 * @param   array   $context    Timber context
	 * @return  array   $context    Adapted Timber context
	 */
	public function filter_timber_context( $context ) {
		Timber\Helper::function_wrapper( 'get_timber_image_responsive_acf' );
		return $context;
	}

	/**
	 * Sets filters to use Timmy functions in Twig
	 *
	 * @param   Twig_Environment    $twig
	 * @return  Twig_Environment    $twig
	 */
	public function filter_get_twig( $twig ) {
		$twig->addFilter( new Twig_SimpleFilter( 'get_timber_image', 'get_timber_image' ) );
		$twig->addFilter( new Twig_SimpleFilter( 'get_timber_image_src', 'get_timber_image_src' ) );
		$twig->addFilter( new Twig_SimpleFilter( 'get_timber_image_responsive', 'get_timber_image_responsive' ) );
		$twig->addFilter( new Twig_SimpleFilter( 'get_timber_image_responsive_src', 'get_timber_image_responsive_src' ) );

		return $twig;
	}

	/**
	 * Define global $_wp_additional_image_sizes with Timmy sizes
	 *
	 * Many WordPress functions and plugins rely on this global variable to
	 * integrate with images. We want this functionality to return all image
	 * sizes we defined ourselves.
	 *
	 * @since 0.10.0
	 */
	public function set_wp_additional_image_sizes() {
		global $_wp_additional_image_sizes;

		foreach ( get_image_sizes() as $key => $size ) {
			$sizes[] = $key;

			$width = absint( $size['resize'][0] );
			$height = isset( $size['resize'][1] ) ? absint( $size['resize'][1] ) : 0;
			$crop = isset( $size['resize'][1] ) ? true : false;

			$_wp_additional_image_sizes[ $key ] = array(
				'width'  => $width,
				'height' => $height,
				'crop'   => $crop,
			);
		}
	}

	public function filter_intermediate_image_sizes( $sizes ) {
		return array_keys( get_image_sizes() );
	}

	/**
	 * Filter the image sizes automatically generated when uploading an image.
	 *
	 * We tell WordPress that we don’t have intermediate sizes, because
	 * we have our own image thingy we want to work with.
	 *
	 * @since 0.10.0
	 */
	public function filter_intermediate_image_sizes_advanced( $sizes ) {
		return array();
	}

	/**
	 * Hook into the filter that generates additional image sizes
	 * to generate all additional image size with TimberImageHelper
	 *
	 * This function will also run if you run Regenerate Thumbnails,
	 * so all additional images sizes registered with Timber will be
	 * first deleted and then regenerated through Timber.
	 *
	 * @param array $metadata
	 * @param int   $attachment_id
	 *
	 * @return  array    $metadata
	 */
	public function filter_wp_generate_attachment_metadata( $metadata, $attachment_id ) {
		if ( wp_attachment_is_image( $attachment_id ) ) {
			$this->timber_generate_sizes( $attachment_id );
		}

		return $metadata;
	}

	/**
	 * Replace the default image sizes with the sizes from the image config.
	 *
	 * The image will only be shown if the config key 'show_in_ui' is not false.
	 *
	 * This filter will also define the sizes that are returned for the Media Grid
	 * in the backend. We make sure to not include any of your own image sizes in
	 * that view, except for 'thumbnail'.
	 */
	public function filter_image_size_names_choose( $sizes = array() ) {
		// We start from scratch and build our own sizes array
		$sizes = array();
		$img_sizes = get_image_sizes();

		/**
		 * When media files are requested through an AJAX call, an action will
		 * be present in $_POST.
		 *
		 * @since 0.10.3
		 */
		$action = isset( $_POST['action'] )
			? filter_var( $_POST['action'], FILTER_SANITIZE_STRING )
			: false;

		foreach ( $img_sizes as $key => $size ) {
			/**
			 * Do not add our own size if it is set to false in the image config
			 * or if it is the Media Grid that is requesting image sizes.
			 */
			if ( isset( $size['show_in_ui'] ) && false === $size['show_in_ui']
				|| 'query-attachments' === $action
			) {
				continue;
			}

			$name = $key;

			if ( isset( $size['name'] ) ) {
				$name = $size['name'] . ' (' . $key . ')';
			}

			$sizes[ $key ] = $name;
		}

		/**
		 * Re-add full size so it can still be selected.
		 *
		 * The full size is needed, if a e.g. a logo has to be
		 * displayed in the page content and no predefined size
		 * fits.
		 */
		$sizes['full'] = __( 'Full Size' );

		return $sizes;
	}

	/**
	 * Add the same sizes to ACF image field options as when we choose an image
	 * size in the content editor.
	 *
	 * @since 0.10.0
	 *
	 * @param  array $sizes     Sizes prepared by ACF
	 * @return array            Our own image sizes
	 */
	public function filter_acf_get_image_sizes( $sizes ) {
		return $this->filter_image_size_names_choose();
	}

	/**
	 * Creates an image size based on the parameters given in the image configuration
	 *
	 * @param bool          $return         Whether to short-circuit the image downsize.
	 * @param int           $attachment_id  Attachment ID for image.
	 * @param array|string  $size           Size of image. Image size or array of width
	 *                                      and height values (in that order).
	 *
	 * @return false|array  Array containing the image URL, width, height, and boolean for
	 *                      whether the image is an intermediate size. False on failure.
	 */
	public function filter_image_downsize( $return = false, $attachment_id, $size ) {
		$attachment = get_post( $attachment_id );

		/**
		 * Bail out if we try to downsize an SVG file or if we are in the backend
		 * and want to load a GIF.
		 *
		 * GIFs are also resized, but only the first still.
		 * We want to load the full GIF only in the frontend, because GIFs might
		 * get quite big. It would slow down the backend big time, if we wanted
		 * to load the media library with several GIFs.
		 */
		if ( 'image/svg+xml' === $attachment->post_mime_type ) {
			return $return;
		} elseif ( ! is_admin() && 'image/gif' === $attachment->post_mime_type ) {
			return $return;
		}

		$img_sizes = get_image_sizes();

		// Sort out which image size we need to take from our own image configuration
		if ( ! is_array( $size ) && isset( $img_sizes[ $size ] ) ) {
			$img_size = $img_sizes[ $size ];

			$should_resize = $this->timber_should_resize( $attachment->post_parent, $img_sizes[ $size ] );
			if ( ! $should_resize ) {
				return $return;
			}
		} else {
			/**
			 * When an image is requested without a size name or with
			 * dimensions only, we try to return the thumbnail. Otherwise
			 * we just take the first image in the image array.
			 */
			if ( isset( $img_sizes['thumbnail'] ) ) {
				$img_size = $img_sizes['thumbnail'];
			} else {
				$img_size = reset( $img_sizes );
			}
		}

		// Timber needs the file src as an url
		$file_src = wp_get_attachment_url( $attachment_id );

		/**
		 * Certain functions ask for the full size of an image
		 *
		 * WP SEO for example asks for the original size, which we’ll return here.
		 */
		if ( in_array( $size, array( 'original', 'full' ), true ) ) {
			$file_meta = wp_get_attachment_metadata( $attachment_id );
			if ( isset( $file_meta['width'] ) && isset( $file_meta['height'] ) ) {
				return array( $file_src, $file_meta['width'], $file_meta['height'], false );
			}
		}

		$resize   = $img_size['resize'];

		$width    = $resize[0];
		$height   = isset( $resize[1] ) ? $resize[1] : 0;
		$crop     = isset( $resize[2] ) ? $resize[2] : 'default';
		$force    = isset( $resize[3] ) ? $resize[3] : false;

		// Resize the image for that size
		$src = self::resize( $img_size, $file_src, $width, $height, $crop, $force );

		// When the input size is an array of width and height
		if ( is_array( $size ) ) {
			$width = $size[0];
			$height = $size[1];
		}

		/**
		 * For the return, we also send in a fourth parameter,
		 * which stands for is_intermediate. It is true if $url is
		 * a resized image, false if it is the original.
		 */
		return array( $src, $width, $height, true );
	}

	/**
	 * Convert an image into a TimberImage.
	 *
	 * @param mixed $timber_image   The ID of the image, an array containing an ID key or
	 *                              an instance of Timber\Image.
	 * @return mixed                Instance of Timber\Image.
	 */
	public static function get_timber_image( $timber_image ) {
		if ( is_numeric( $timber_image ) ) {
			$timber_image = new Timber\Image( $timber_image );

		} elseif ( is_array( $timber_image ) && isset( $timber_image['ID'] ) ) {
			$timber_image = new Timber\Image( $timber_image['ID'] );
		}

		// Check if non-empty TimberImage was found before returning it
		if ( ! $timber_image instanceof Timber\Image
			 || ! isset( $timber_image->post_type )
		     || 'attachment' !== $timber_image->post_type
		) {
			return false;
		}

		return $timber_image;
	}

	/**
	 * Get an array with image parameters required for generating a new size.
	 *
	 * @since 0.10.0
	 *
	 * @param Timber\Image|int  $timber_image   Instance of TimberImage
	 * @param array             $img_size       Image configuration array for image size to be used
	 * @return array                            An non-associative array with $file_src, $width,
	 *                                          $height, $crop, $force, $max_width, $undersized
	 *                                          (in that order). Thought to be used with list().
	 */
	public static function get_image_params( $timber_image, $img_size ) {
		$file_src   = $timber_image->src();
		$max_width  = $timber_image->width();
		$max_height = $timber_image->height();

		// Defaults
		$oversize_defaults = array(
			'allow' => false,
			'style_attr' => true,
		);

		if ( ! isset( $img_size['oversize'] ) ) {
			$oversize = array( 'allow' => false );
		} else {
			if ( ! is_array( $img_size['oversize'] ) ) {
				$oversize = array( 'allow' => $img_size['oversize'] );
			} else {
				$oversize = $img_size['oversize'];
			}
		}

		$oversize = wp_parse_args( $oversize, $oversize_defaults );

		$resize = $img_size['resize'];

		// Get values for the default image size
		$width  = $resize[0];
		$height = isset( $resize[1] ) ? $resize[1] : 0;

		if ( ! $oversize['allow'] ) {
			$restrict = $oversize['style_attr'];

			/**
			 * Check whether the image source width is smaller than the desired width
			 * or the image source height is smaller than the desired height.
			 */
			if ( $width > $max_width ) {
				// Overwrite $width to use a max width
				$width = $max_width;

				// Calculate new height based on new width
				if ( isset( $resize[1] ) ) {
					$height = (int) round( $width * ( $resize[1] / $resize[0] ) );
				}

				$restrict = 'width';

			} else if ( $height > 0 && $height > $max_height ) {
				$height = $max_height;
				$width = (int) round( $max_width / $max_height * $height );

				$restrict = 'height';
			}

			if ( false !== $restrict ) {
				$oversize['style_attr'] = $restrict;
			}
		}

		$crop   = isset( $resize[2] ) ? $resize[2] : 'default';
		$force  = isset( $resize[3] ) ? $resize[3] : false;

		return array(
			$file_src,
			$width,
			$height,
			$crop,
			$force,
			$max_width,
			$max_height,
			$oversize,
		);
	}

	/**
	 * Resize an image and apply letterbox and tojpg filters when defined.
	 *
	 * @since 0.9.2
	 *
	 * @param  array    $img_size 	Configuration values for an image size
	 * @param  string   $file_src   The src of the original image
	 * @param  int      $width    	The width the new image should be resized to
	 * @param  int      $height   	The height the new image should be resized to
	 * @param  string   $crop       Cropping option
	 * @param  bool     $force    	Force cropping
	 * @return string               The src of the image
	 */
	public static function resize( $img_size, $file_src, $width, $height, $crop, $force ) {
		// Check if image should be converted to jpg first
		if ( isset( $img_size['tojpg'] ) && $img_size['tojpg'] ) {
			// Sort out background color which will show instead of transparency
			$bgcolor = is_string( $img_size['tojpg'] ) ? $img_size['tojpg'] : '#FFFFFF';
			$file_src = Timber\ImageHelper::img_to_jpg( $file_src, $bgcolor, $force );
		}

		// Check for letterbox parameter
		if ( isset( $img_size['letterbox'] ) && $img_size['letterbox']
			&& $width > 0 && $height > 0
		) {
			$color = is_string( $img_size['letterbox'] ) ? $img_size['letterbox'] : '#000000';
			return Timber\ImageHelper::letterbox( $file_src, $width, $height, $color );
		} else {
			return Timber\ImageHelper::resize( $file_src, $width, $height, $crop, $force );
		}
	}

	/**
	 * Get the actual width at which the image will be displayed.
	 *
	 * When 0 is passed to Timber as a width, it calculates the image ratio based on
	 * the height of the image. We have to account for that, when we use the responsive
	 * image, because in the srcset, there cant be a value like "image.jpg 0w". So we
	 * have to calculate the width based on the values we have.
	 *
	 * @since 0.9.3
	 *
	 * @param  int          $width          The value of the resize parameter for width
	 * @param  int          $height         The value of the resize parameter for height
	 * @param  Timber\Image $timber_image   Instance of TimberImage
	 * @return int                          The width at which the image will be displayed.
	 */
	public static function get_width_key( $width, $height, $timber_image ) {
		if ( 0 === $width ) {
			/**
			 * Calculate image width based on image ratio and height.
			 * We need a rounded value because we will use this number as an
			 * array key and for defining the srcset size in pixel values.
			 */
			return (int) round( $timber_image->aspect() * $height );
		}

		return $width;
	}

	/**
	 * Generate image sizes defined for Timmy with TimberImageHelper.
	 *
	 * @param  int	$attachment_id	The attachment ID for which all images should be resized
	 * @return void
	 */
	private function timber_generate_sizes( $attachment_id ) {
		$img_sizes = get_image_sizes();
		$attachment = get_post( $attachment_id );

		// Timber needs the file src as an url
		$file_src = wp_get_attachment_url( $attachment_id );

		/**
		 * Delete all existing image sizes for that file.
		 *
		 * This way, when Regenerate Thumbnails will be used,
		 * all non-registered image sizes will be deleted as well.
		 * Because Timber creates image sizes when they’re needed,
		 * we can safely do this.
		 */
		Timber\ImageHelper::delete_generated_files( $file_src );

		foreach ($img_sizes as $key => $img_size) {
			if ( ! $this->timber_should_resize( $attachment->post_parent, $img_size ) ) {
				continue;
			}

			$resize = $img_size['resize'];

			// Get values for the default image
			$crop     = isset( $resize[2] ) ? $resize[2] : 'default';
			$force    = isset( $resize[3] ) ? $resize[3] : false;

			image_downsize( $attachment_id, $key );

			if ( isset( $img_size['generate_srcset_sizes'] ) && false === $img_size['generate_srcset_sizes'] ) {
				continue;
			}

			// Generate additional image sizes used for srcset
			if ( isset( $img_size['srcset'] ) ) {
				foreach ($img_size['srcset'] as $src) {
					// Get width and height for the additional src
					if ( is_array( $src ) ) {
						$width = $src[0];
						$height = isset( $src[1] ) ? $src[1] : 0;
					} else {
						$width = (int) round( $resize[0] * $src );
						$height = isset( $resize[1] ) ? (int) round( $resize[1] * $src ) : 0;
					}

					// For the new source, we use the same $crop and $force values as the default image
					self::resize( $img_size, $file_src, $width, $height, $crop, $force );
				}
			}
		}
	}

	/**
	 * Check if we should pregenerate an image size based on the image configuration.
	 *
	 * @param  int      $attachment_parent_id
	 * @param  string   $img_size               The key of the size in the image configuration
	 * @return bool                             Whether the image should or can be resized
	 */
	private function timber_should_resize( $attachment_parent_id, $img_size ) {
		/**
		 * Normally we don’t have a post type associated with the attachment
		 *
		 * We use an empty array to tell the function that there is no post type
		 * associated with the attachment.
		 */
		$attachment_post_type = array( '' );

		// Check if image is attached to a post and sort out post type
		if ( 0 !== $attachment_parent_id ) {
			$parent = get_post( $attachment_parent_id );
			$attachment_post_type = array( $parent->post_type );
		}

		// Reset post types that should be applied as a standard
		$post_types_to_apply = array( '', 'page', 'post' );

		/**
		 * When a post type is given in the arguments, we generate the size
		 * only if the attachment is associated with that post.
		 */
		if ( array_key_exists( 'post_types', $img_size ) ) {
			$post_types_to_apply = $img_size['post_types'];
		}

		if ( ! in_array( 'all', $post_types_to_apply, true ) ) {
			// Check if we should really resize that picture
			$intersections = array_intersect( $post_types_to_apply, $attachment_post_type );

			if ( ! empty( $intersections ) ) {
				return true;
			}
		} else {
			return true;
		}

		return false;
	}
}
