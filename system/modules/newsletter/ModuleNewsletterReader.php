<?php

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2012 Leo Feyer
 *
 * Formerly known as TYPOlight Open Source CMS.
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 3 of the License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at <http://www.gnu.org/licenses/>.
 *
 * PHP version 5.3
 * @copyright  Leo Feyer 2005-2012
 * @author     Leo Feyer <http://www.contao.org>
 * @package    News
 * @license    LGPL
 */


/**
 * Run in a custom namespace, so the class can be replaced
 */
namespace Contao;


/**
 * Class ModuleNewsletterReader
 *
 * Front end module "newsletter reader".
 * @copyright  Leo Feyer 2005-2012
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Controller
 */
class ModuleNewsletterReader extends \Module
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'mod_newsletter_reader';


	/**
	 * Display a wildcard in the back end
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			$objTemplate = new \BackendTemplate('be_wildcard');

			$objTemplate->wildcard = '### NEWSLETTER READER ###';
			$objTemplate->title = $this->headline;
			$objTemplate->id = $this->id;
			$objTemplate->link = $this->name;
			$objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

			return $objTemplate->parse();
		}

		// Set the item from the auto_item parameter
		if ($GLOBALS['TL_CONFIG']['useAutoItem'] && isset($_GET['auto_item']))
		{
			$this->Input->setGet('items', $this->Input->get('auto_item'));
		}

		// Do not index or cache the page if no news item has been specified
		if (!$this->Input->get('items'))
		{
			global $objPage;
			$objPage->noSearch = 1;
			$objPage->cache = 0;
			return '';
		}

		$this->nl_channels = deserialize($this->nl_channels);

		// Do not index or cache the page if there are no channels
		if (!is_array($this->nl_channels) || empty($this->nl_channels))
		{
			global $objPage;
			$objPage->noSearch = 1;
			$objPage->cache = 0;
			return '';
		}

		return parent::generate();
	}


	/**
	 * Generate the module
	 * @return void
	 */
	protected function compile()
	{
		global $objPage;

		$this->Template->content = '';
		$this->Template->referer = 'javascript:history.go(-1)';
		$this->Template->back = $GLOBALS['TL_LANG']['MSC']['goBack'];

		$objNewsletter = \NewsletterModel::findSentByParentAndIdOrAlias((is_numeric($this->Input->get('items')) ? $this->Input->get('items') : 0), $this->Input->get('items'), $this->nl_channels);

		if ($objNewsletter === null)
		{
			// Do not index or cache the page
			$objPage->noSearch = 1;
			$objPage->cache = 0;

			// Send a 404 header
			header('HTTP/1.1 404 Not Found');
			$this->Template->content = '<p class="error">' . sprintf($GLOBALS['TL_LANG']['MSC']['invalidPage'], $this->Input->get('items')) . '</p>';
			return;
		}

		$arrEnclosures = array();

		// Add enclosure
		if ($objNewsletter->addFile)
		{
			$arrEnclosure = deserialize($objNewsletter->files, true);
			$allowedDownload = trimsplit(',', strtolower($GLOBALS['TL_CONFIG']['allowedDownload']));

			if (is_array($arrEnclosure))
			{
				// Send file to the browser
				if ($this->Input->get('file', true) != '' && in_array($this->Input->get('file', true), $arrEnclosure))
				{
					$this->sendFileToBrowser($this->Input->get('file', true));
				}

				// Add download links
				for ($i=0; $i<count($arrEnclosure); $i++)
				{
					if (is_file(TL_ROOT . '/' . $arrEnclosure[$i]))
					{				
						$objFile = new \File($arrEnclosure[$i]);

						if (in_array($objFile->extension, $allowedDownload))
						{
							$src = 'system/themes/' . $this->getTheme() . '/images/' . $objFile->icon;

							if (($imgSize = @getimagesize(TL_ROOT . '/' . $src)) !== false)
							{
								$arrEnclosures[$i]['size'] = ' ' . $imgSize[3];
							}

							$arrEnclosures[$i]['icon'] = TL_FILES_URL . $src;
							$arrEnclosures[$i]['link'] = basename($arrEnclosure[$i]);
							$arrEnclosures[$i]['filesize'] = $this->getReadableSize($objFile->filesize);
							$arrEnclosures[$i]['title'] = ucfirst(str_replace('_', ' ', $objFile->filename));
							$arrEnclosures[$i]['href'] = $this->Environment->request . (($GLOBALS['TL_CONFIG']['disableAlias'] || strpos($this->Environment->request, '?') !== false) ? '&amp;' : '?') . 'file=' . $this->urlEncode($arrEnclosure[$i]);
							$arrEnclosures[$i]['enclosure'] = $arrEnclosure[$i];
						}
					}
				}
			}
		}

		// Support plain text newsletters (thanks to Hagen Klemp)
		if ($objNewsletter->sendText)
		{
			$nl2br = ($objPage->outputFormat == 'xhtml') ? 'nl2br_xhtml' : 'nl2br_html5';
			$strContent = $nl2br($objNewsletter->text);
		}
		else
		{
			$strContent = str_ireplace(' align="center"', '', $objNewsletter->content);
		}

		// Parse simple tokens and insert tags
		$strContent = $this->replaceInsertTags($strContent);
		$strContent = $this->parseSimpleTokens($strContent, array());

		// Encode e-mail addresses
		$this->import('String');
		$strContent = $this->String->encodeEmail($strContent);

		$this->Template->content = $strContent;
		$this->Template->subject = $objNewsletter->subject;
		$this->Template->enclosure = $arrEnclosures;
	}
}
