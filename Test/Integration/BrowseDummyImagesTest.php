<?php
declare(strict_types=1);

namespace Yireo\Webp2\Test\Integration;

use Magento\Framework\View\LayoutInterface;

/**
 * Class BrowseDummyImagesTest
 * @package Yireo\Webp2\Test\Integration
 */
class BrowseDummyImagesTest extends Common
{
    /**
     * @magentoAdminConfigFixture system/yireo_webp/enabled 1
     * @magentoAdminConfigFixture system/yireo_webp/debug 1
     */
    public function testIfHtmlContainsWebpImages()
    {
        $this->fixtureImageFiles();

        $this->getResponse()->setHeader('Accept', 'image/webp');
        $this->dispatch('webp/test/images/case/multiple');

        /** @var LayoutInterface $layout */
        $layout = $this->_objectManager->get(LayoutInterface::class);
        $body = $layout->getOutput();

        $this->assertImageTagsExist($body, $this->getImageProvider()->getImages());
    }

    /**
     * @magentoAdminConfigFixture system/yireo_webp/enabled 1
     * @magentoAdminConfigFixture system/yireo_webp/debug 1
     */
    public function testIfHtmlContainsSingleWebpImage()
    {
        $this->fixtureImageFiles();

        $this->getResponse()->setHeader('Accept', 'image/webp');
        $this->dispatch('webp/test/images/case/multiple_same');

        /** @var LayoutInterface $layout */
        $layout = $this->_objectManager->get(LayoutInterface::class);
        $body = $layout->getOutput();

        $this->assertImageTagsExist($body, [$this->getImageProvider()->getImage()]);
    }

    /**
     * @param string $body
     */
    private function assertImageTagsExist(string $body, $images)
    {
        foreach ($images as $image) {
            $webPImage = preg_replace('/\.(png|jpg)$/', '.webp', $image);
            $this->assertContains($webPImage, $body);
        }
    }
}
