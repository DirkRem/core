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
 * @package    Backend
 * @license    LGPL
 */


/**
 * Table tl_layout
 */
$GLOBALS['TL_DCA']['tl_layout'] = array
(

	// Config
	'config' => array
	(
		'dataContainer'               => 'Table',
		'ptable'                      => 'tl_theme',
		'enableVersioning'            => true,
		'onload_callback' => array
		(
			array('tl_layout', 'checkPermission')
		),
		'sql' => array
		(
			'keys' => array
			(
				'id' => 'primary'
			)
		)
	),

	// List
	'list' => array
	(
		'sorting' => array
		(
			'mode'                    => 4,
			'fields'                  => array('name'),
			'panelLayout'             => 'filter;sort,search,limit',
			'headerFields'            => array('name', 'author', 'tstamp'),
			'child_record_callback'   => array('tl_layout', 'listLayout'),
			'child_record_class'      => 'no_padding'
		),
		'global_operations' => array
		(
			'all' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['MSC']['all'],
				'href'                => 'act=select',
				'class'               => 'header_edit_all',
				'attributes'          => 'onclick="Backend.getScrollOffset()" accesskey="e"'
			)
		),
		'operations' => array
		(
			'edit' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_layout']['edit'],
				'href'                => 'act=edit',
				'icon'                => 'edit.gif'
			),
			'copy' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_layout']['copy'],
				'href'                => 'act=paste&amp;mode=copy',
				'icon'                => 'copy.gif'
			),
			'cut' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_layout']['cut'],
				'href'                => 'act=paste&amp;mode=cut',
				'icon'                => 'cut.gif',
				'attributes'          => 'onclick="Backend.getScrollOffset()"'
			),
			'delete' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_layout']['delete'],
				'href'                => 'act=delete',
				'icon'                => 'delete.gif',
				'attributes'          => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"'
			),
			'show' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_layout']['show'],
				'href'                => 'act=show',
				'icon'                => 'show.gif'
			)
		)
	),

	// Palettes
	'palettes' => array
	(
		'__selector__'                => array('header', 'footer', 'cols', 'static'),
		'default'                     => '{title_legend},name,fallback;{header_legend},header,footer;{column_legend},cols;{sections_legend:hide},sections,sPosition;{style_legend},stylesheet,skipTinymce,external;{feed_legend:hide},newsfeeds,calendarfeeds;{modules_legend},modules;{expert_legend:hide},template,skipFramework,doctype,webfonts,cssClass,onload,head;{script_legend},mooSource,jSource,mootools,jquery,script;{static_legend},static'
	),

	// Subpalettes
	'subpalettes' => array
	(
		'header'                      => 'headerHeight',
		'footer'                      => 'footerHeight',
		'cols_2cll'                   => 'widthLeft',
		'cols_2clr'                   => 'widthRight',
		'cols_3cl'                    => 'widthLeft,widthRight',
		'static'                      => 'width,align'
	),

	// Fields
	'fields' => array
	(
		'id' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL auto_increment"
		),
		'pid' => array
		(
			'foreignKey'              => 'tl_theme.name',
			'sql'                     => "int(10) unsigned NOT NULL default '0'",
			'relation'                => array('type'=>'belongsTo', 'load'=>'eager')
		),
		'tstamp' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL default '0'"
		),
		'name' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_layout']['name'],
			'inputType'               => 'text',
			'exclude'                 => true,
			'sorting'                 => true,
			'flag'                    => 1,
			'search'                  => true,
			'eval'                    => array('mandatory'=>true, 'maxlength'=>255),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'fallback' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_layout']['fallback'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('fallback'=>true),
			'sql'                     => "char(1) NOT NULL default ''"
		),
		'header' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_layout']['header'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('submitOnChange'=>true),
			'sql'                     => "char(1) NOT NULL default ''"
		),
		'headerHeight' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_layout']['headerHeight'],
			'exclude'                 => true,
			'inputType'               => 'inputUnit',
			'options'                 => array('px', '%', 'em', 'ex', 'pt', 'pc', 'in', 'cm', 'mm'),
			'eval'                    => array('includeBlankOption'=>true, 'rgxp'=>'digit'),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'footer' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_layout']['footer'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('submitOnChange'=>true),
			'sql'                     => "char(1) NOT NULL default ''"
		),
		'footerHeight' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_layout']['footerHeight'],
			'exclude'                 => true,
			'inputType'               => 'inputUnit',
			'options'                 => array('px', '%', 'em', 'ex', 'pt', 'pc', 'in', 'cm', 'mm'),
			'eval'                    => array('includeBlankOption'=>true, 'rgxp'=>'digit'),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'cols' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_layout']['cols'],
			'default'                 => '2cll',
			'exclude'                 => true,
			'inputType'               => 'radioTable',
			'options'                 => array('1cl', '2cll', '2clr', '3cl'),
			'eval'                    => array('helpwizard'=>true, 'cols'=>4, 'submitOnChange'=>true),
			'reference'               => &$GLOBALS['TL_LANG']['tl_layout'],
			'sql'                     => "varchar(32) NOT NULL default ''"
		),
		'widthLeft' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_layout']['widthLeft'],
			'exclude'                 => true,
			'inputType'               => 'inputUnit',
			'options'                 => array('px', '%', 'em', 'ex', 'pt', 'pc', 'in', 'cm', 'mm'),
			'eval'                    => array('includeBlankOption'=>true, 'rgxp'=>'digit', 'tl_class'=>'w50'),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'widthRight' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_layout']['widthRight'],
			'exclude'                 => true,
			'inputType'               => 'inputUnit',
			'options'                 => array('px', '%', 'em', 'ex', 'pt', 'pc', 'in', 'cm', 'mm'),
			'eval'                    => array('includeBlankOption'=>true, 'rgxp'=>'digit', 'tl_class'=>'w50'),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'sections' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_layout']['sections'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'checkbox',
			'options'                 => trimsplit(',', $GLOBALS['TL_CONFIG']['customSections']),
			'eval'                    => array('multiple'=>true),
			'sql'                     => "blob NULL"
		),
		'sPosition' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_layout']['sPosition'],
			'default'                 => 'main',
			'exclude'                 => true,
			'inputType'               => 'select',
			'options'                 => array('before', 'main', 'after'),
			'reference'               => &$GLOBALS['TL_LANG']['tl_layout'],
			'sql'                     => "varchar(32) NOT NULL default ''"
		),
		'stylesheet' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_layout']['stylesheet'],
			'exclude'                 => true,
			'filter'                  => true,
			'inputType'               => 'checkboxWizard',
			'foreignKey'              => 'tl_style_sheet.name',
			'options_callback'        => array('tl_layout', 'getStyleSheets'),
			'eval'                    => array('multiple'=>true),
			'xlabel' => array
			(
				array('tl_layout', 'styleSheetLink')
			),
			'sql'                     => "blob NULL",
			'relation'                => array('type'=>'hasMany', 'load'=>'lazy')
		),
		'skipTinymce' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_layout']['skipTinymce'],
			'default'                 => 1,
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'sql'                     => "char(1) NOT NULL default ''"
		),
		'external' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_layout']['external'],
			'exclude'                 => true,
			'inputType'               => 'listWizard',
			'eval'                    => array('style'=>'width:360px'),
			'save_callback' => array
			(
				array('tl_layout', 'filterExternalPaths')
			),
			'sql'                     => "blob NULL"
		),
		'newsfeeds' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_layout']['newsfeeds'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'options_callback'        => array('tl_layout', 'getNewsfeeds'),
			'eval'                    => array('multiple'=>true),
			'sql'                     => "blob NULL"
		),
		'calendarfeeds' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_layout']['calendarfeeds'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'options_callback'        => array('tl_layout', 'getCalendarfeeds'),
			'eval'                    => array('multiple'=>true),
			'sql'                     => "blob NULL"
		),
		'modules' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_layout']['modules'],
			'default'                 => array(array('mod'=>0, 'col'=>'main')),
			'exclude'                 => true,
			'inputType'               => 'moduleWizard',
			'sql'                     => "blob NULL"
		),
		'template' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_layout']['template'],
			'exclude'                 => true,
			'filter'                  => true,
			'search'                  => true,
			'sorting'                 => true,
			'flag'                    => 11,
			'inputType'               => 'select',
			'options_callback'        => array('tl_layout', 'getPageTemplates'),
			'eval'                    => array('tl_class'=>'w50'),
			'sql'                     => "varchar(64) NOT NULL default ''"
		),
		'skipFramework' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_layout']['skipFramework'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('tl_class'=>'w50 m12'),
			'sql'                     => "char(1) NOT NULL default ''"
		),
		'doctype' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_layout']['doctype'],
			'exclude'                 => true,
			'filter'                  => true,
			'sorting'                 => true,
			'flag'                    => 11,
			'inputType'               => 'select',
			'options'                 => array('html5', 'xhtml_strict', 'xhtml_trans'),
			'reference'               => &$GLOBALS['TL_LANG']['tl_layout'],
			'eval'                    => array('tl_class'=>'w50'),
			'sql'                     => "varchar(32) NOT NULL default ''"
		),
		'webfonts' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_layout']['webfonts'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('decodeEntities'=>true, 'maxlength'=>255, 'tl_class'=>'w50'),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'cssClass' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_layout']['cssClass'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('decodeEntities'=>true, 'maxlength'=>255, 'tl_class'=>'w50'),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'onload' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_layout']['onload'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('decodeEntities'=>true, 'maxlength'=>255, 'tl_class'=>'w50'),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'head' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_layout']['head'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'textarea',
			'eval'                    => array('style'=>'height:60px;', 'preserveTags'=>true, 'tl_class'=>'clr'),
			'sql'                     => "text NULL"
		),
		'mooSource' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_layout']['mooSource'],
			'default'                 => 'moo_local',
			'exclude'                 => true,
			'inputType'               => 'select',
			'options'                 => array('moo_local', 'moo_googleapis', 'moo_fallback'),
			'reference'               => &$GLOBALS['TL_LANG']['tl_layout'],
			'eval'                    => array('includeBlankOption'=>true, 'tl_class'=>'w50'),
			'sql'                     => "varchar(16) NOT NULL default ''"
		),
		'jSource' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_layout']['jSource'],
			'exclude'                 => true,
			'inputType'               => 'select',
			'options'                 => array('j_local', 'j_googleapis', 'j_fallback'),
			'reference'               => &$GLOBALS['TL_LANG']['tl_layout'],
			'eval'                    => array('includeBlankOption'=>true, 'tl_class'=>'w50'),
			'sql'                     => "varchar(16) NOT NULL default ''"
		),
		'mootools' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_layout']['mootools'],
			'exclude'                 => true,
			'filter'                  => true,
			'search'                  => true,
			'inputType'               => 'checkboxWizard',
			'options_callback'        => array('tl_layout', 'getMooToolsTemplates'),
			'eval'                    => array('multiple'=>true, 'tl_class'=>'w50 autoheight'),
			'sql'                     => "text NULL"
		),
		'jquery' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_layout']['jquery'],
			'exclude'                 => true,
			'filter'                  => true,
			'search'                  => true,
			'inputType'               => 'checkboxWizard',
			'options_callback'        => array('tl_layout', 'getJqueryTemplates'),
			'eval'                    => array('multiple'=>true, 'tl_class'=>'w50 autoheight'),
			'sql'                     => "text NULL"
		),
		'script' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_layout']['script'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'textarea',
			'eval'                    => array('style'=>'height:120px', 'preserveTags'=>true, 'tl_class'=>'clr'),
			'sql'                     => "text NULL"
		),
		'static' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_layout']['static'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('submitOnChange'=>true),
			'sql'                     => "char(1) NOT NULL default ''"
		),
		'width' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_layout']['width'],
			'exclude'                 => true,
			'inputType'               => 'inputUnit',
			'options'                 => array('px', '%', 'em', 'ex', 'pt', 'pc', 'in', 'cm', 'mm'),
			'eval'                    => array('includeBlankOption'=>true, 'rgxp'=>'digit', 'tl_class'=>'w50'),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'align' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_layout']['align'],
			'default'                 => 'center',
			'exclude'                 => true,
			'inputType'               => 'select',
			'options'                 => array('left', 'center', 'right'),
			'reference'               => &$GLOBALS['TL_LANG']['MSC'],
			'eval'                    => array('tl_class'=>'w50'),
			'sql'                     => "varchar(32) NOT NULL default ''"
		)
	)
);


/**
 * Class tl_layout
 *
 * Provide miscellaneous methods that are used by the data configuration array.
 * @copyright  Leo Feyer 2005-2012
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Controller
 */
class tl_layout extends Backend
{

	/**
	 * Import the back end user object
	 */
	public function __construct()
	{
		parent::__construct();
		$this->import('BackendUser', 'User');
	}


	/**
	 * Check permissions to edit the table
	 * @return void
	 */
	public function checkPermission()
	{
		if ($this->User->isAdmin)
		{
			return;
		}

		if (!$this->User->hasAccess('layout', 'themes'))
		{
			$this->log('Not enough permissions to access the page layout module', 'tl_layout checkPermission', TL_ERROR);
			$this->redirect('contao/main.php?act=error');
		}
	}


	/**
	 * Return all style sheets of the current theme
	 * @param \DataContainer
	 * @return array
	 */
	public function getStyleSheets(\DataContainer $dc)
	{
		$intPid = $dc->activeRecord->pid;

		if ($this->Input->get('act') == 'overrideAll')
		{
			$intPid = $this->Input->get('id');
		}

		$objStyleSheet = $this->Database->prepare("SELECT id, name FROM tl_style_sheet WHERE pid=?")
										->execute($intPid);

		if ($objStyleSheet->numRows < 1)
		{
			return array();
		}

		$return = array();

		while ($objStyleSheet->next())
		{
			$return[$objStyleSheet->id] = $objStyleSheet->name;
		}

		return $return;
	}


	/**
	 * Return all news archives with XML feeds
	 * @return array
	 */
	public function getNewsfeeds()
	{
		$objFeed = \NewsFeedCollection::findAll();

		if ($objFeed === null)
		{
			return array();
		}

		$return = array();

		while ($objFeed->next())
		{
			$return[$objFeed->id] = $objFeed->title;
		}

		return $return;
	}


	/**
	 * Return all calendars with XML feeds
	 * @return array
	 */
	public function getCalendarfeeds()
	{
		$objFeed = \CalendarFeedCollection::findAll();

		if ($objFeed === null)
		{
			return array();
		}

		$return = array();

		while ($objFeed->next())
		{
			$return[$objFeed->id] = $objFeed->title;
		}

		return $return;
	}


	/**
	 * Return all page templates as array
	 * @param \DataContainer
	 * @return array
	 */
	public function getPageTemplates(\DataContainer $dc)
	{
		$intPid = $dc->activeRecord->pid;

		if ($this->Input->get('act') == 'overrideAll')
		{
			$intPid = $this->Input->get('id');
		}

		return $this->getTemplateGroup('fe_', $intPid);
	}


	/**
	 * Return all MooTools templates as array
	 * @param \DataContainer
	 * @return array
	 */
	public function getMooToolsTemplates(\DataContainer $dc)
	{
		$intPid = $dc->activeRecord->pid;

		if ($this->Input->get('act') == 'overrideAll')
		{
			$intPid = $this->Input->get('id');
		}

		return $this->getTemplateGroup('moo_', $intPid);
	}


	/**
	 * Return all jQuery templates as array
	 * @param \DataContainer
	 * @return array
	 */
	public function getJqueryTemplates(\DataContainer $dc)
	{
		$intPid = $dc->activeRecord->pid;

		if ($this->Input->get('act') == 'overrideAll')
		{
			$intPid = $this->Input->get('id');
		}

		return $this->getTemplateGroup('j_', $intPid);
	}
 

	/**
	 * List a page layout
	 * @param array
	 * @return string
	 */
	public function listLayout($row)
	{
		if (!$row['fallback'])
		{
			return '<div style="float:left">'. $row['name'] ."</div>\n";
		}

		return '<div style="float:left">'. $row['name'] .' <span style="color:#b3b3b3; padding-left:3px">['. $GLOBALS['TL_LANG']['MSC']['fallback'] .']</span>' . "</div>\n";
	}


	/**
	 * Remove empty and duplicate elements from the external style sheets array
	 * @param mixed
	 * @return string
	 */
	public function filterExternalPaths($varValue)
	{
		if ($varValue == '')
		{
			return $varValue;
		}

		$varValue = deserialize($varValue);

		if (!is_array($varValue))
		{
			return '';
		}

		return array_filter(array_unique($varValue));
	}


	/**
	 * Add a link to edit the stylesheets of the theme
	 * @param \DataContainer
	 * @return string
	 */
	public function styleSheetLink(\DataContainer $dc)
	{
		return ' <a href="contao/main.php?do=themes&table=tl_style_sheet&id=' . $dc->activeRecord->pid . '" title="' . specialchars($GLOBALS['TL_LANG']['tl_layout']['edit_styles']) . '"><img width="12" height="16" alt="" src="system/themes/' . $this->getTheme() . '/images/edit.gif" style="vertical-align:text-bottom"></a>';
	}
}
