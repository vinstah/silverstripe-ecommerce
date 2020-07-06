<?php

namespace Sunnysideup\Ecommerce\Filesystem;

use SilverStripe\Assets\Image;
use SilverStripe\Assets\Storage\DBFile;
use SilverStripe\ORM\FieldType\DBField;
use SilverStripe\ORM\FieldType\DBHTMLText;
use Sunnysideup\Ecommerce\Config\EcommerceConfig;

class ProductImage extends Image
{

    /**
     * @var int
     */
    private static $thumbnail_width = 75;

    /**
     * @var int
     */
    private static $thumbnail_height = 75;

    /**
     * @var int
     */
    private static $small_image_width = 200;

    /**
     * @var int
     */
    private static $small_image_height = 200;

    /**
     * @var int
     */
    private static $content_image_width = 400;

    /**
     * @var int
     */
    private static $large_image_width = 600;

    /**
     * standard SS variable.
     *
     * @Var String
     */
    private static $singular_name = 'Product Image';

    /**
     * standard SS variable.
     *
     * @Var String
     */
    private static $plural_name = 'Product Images';

    private static $casting = [
        'CMSThumbnail' => 'HTMLText',
    ];

    public function i18n_singular_name()
    {
        return _t('ProductImage.SINGULARNAME', 'Product Image');
    }

    public function i18n_plural_name()
    {
        return _t('ProductImage.PLURALNAME', 'Product Images');
    }

    /**
     * Fields.
     *
     * @return array
     */
    public function summaryFields()
    {
        return [
            'CMSThumbnail' => 'Preview',
            'Title' => 'Title',
        ];
    }

    /**
     * @return int
     */
    public function ThumbWidth()
    {
        return EcommerceConfig::get(ProductImage::class, 'thumbnail_width');
    }

    /**
     * @return int
     */
    public function ThumbHeight()
    {
        return EcommerceConfig::get(ProductImage::class, 'thumbnail_height');
    }

    /**
     * @return int
     */
    public function SmallWidth()
    {
        return EcommerceConfig::get(ProductImage::class, 'small_image_width');
    }

    /**
     * @return int
     */
    public function SmallHeight()
    {
        return EcommerceConfig::get(ProductImage::class, 'small_image_height');
    }

    /**
     * @return int
     */
    public function ContentWidth()
    {
        return EcommerceConfig::get(ProductImage::class, 'content_image_width');
    }

    /**
     * @return int
     */
    public function LargeWidth()
    {
        return EcommerceConfig::get(ProductImage::class, 'large_image_width');
    }

    /**
     * @usage can be used in a template like this $Image.Thumbnail.Link
     *
     * @param resource $gd - GD
     *
     * @return resource - GD
     **/
    public function generateThumbnail($gd)
    {
        $gd->setQuality(65);

        return $gd->paddedResize($this->ThumbWidth() * 2, $this->ThumbHeight() * 2);
    }

    public function Thumbnail($width = 0, $height = 0)
    {
        $width = $width ?: $this->ThumbWidth();
        $height = $height ?: $this->ThumbHeight();
        return $this->Fill($width, $height);
    }

    /**
     * @usage can be used in a template like this $Image.SmallImage.Link
     *
     * @return resource - GD
     **/
    public function generateSmallImage($gd)
    {
        $gd->setQuality(65);

        return $gd->paddedResize($this->SmallWidth() * 2, $this->SmallHeight() * 2);
    }

    public function SmallImage()
    {
        /* TODO UPGRADE: what should getFormattedImage be replace with? */
        //return $this->getFormattedImage('SmallImage');
    }

    /**
     * @usage can be used in a template like this $Image.ContentImage.Link
     *
     * @return resource - GD
     **/
    public function generateContentImage($gd)
    {
        $gd->setQuality(65);

        return $gd->resizeByWidth($this->ContentWidth() * 2);
    }

    public function LargeImage()
    {
        return $this->getFormattedImage('LargeImage');
    }

    /**
     * @usage can be used in a template like this $Image.LargeImage.Link
     *
     * @return resource - GD
     **/
    public function generateLargeImage($gd)
    {
        $gd->setQuality(65);

        return $gd->resizeByWidth($this->LargeWidth() * 2);
    }

    /**
     * @return DBFile|DBHTMLText
     */
    public function CMSThumbnail()
    {
        return $this->getCMSThumbnail();
    }

    /**
     * @return DBFile|DBHTMLText
     */
    public function getCMSThumbnail()
    {
        $smallImage = $this->SmallImage();
        if ($smallImage) {
            $icon = '<img src="' . $smallImage->FileName . '" style="border: 1px solid #555; height: 100px; " />';
        } else {
            $icon = '[MISSING IMAGE]';
        }

        return DBField::create_field('HTMLText', $icon);
    }
}
