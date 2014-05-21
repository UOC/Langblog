<?php
// ===================================================================================================
//                           _  __     _ _
//                          | |/ /__ _| | |_ _  _ _ _ __ _
//                          | ' </ _` | |  _| || | '_/ _` |
//                          |_|\_\__,_|_|\__|\_,_|_| \__,_|
//
// This file is part of the Kaltura Collaborative Media Suite which allows users
// to do with audio, video, and animation what Wiki platfroms allow them to do with
// text.
//
// Copyright (C) 2006-2011  Kaltura Inc.
//
// This program is free software: you can redistribute it and/or modify
// it under the terms of the GNU Affero General Public License as
// published by the Free Software Foundation, either version 3 of the
// License, or (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU Affero General Public License for more details.
//
// You should have received a copy of the GNU Affero General Public License
// along with this program.  If not, see <http://www.gnu.org/licenses/>.
//
// @ignore
// ===================================================================================================
//abertranb add namespace
namespace uploader_kaltura\API\KalturaPlugins
{
use uploader_kaltura\API as KalturaBaseAPI;
/**
 * @package Kaltura
 * @subpackage Client
 */
require_once(dirname(__FILE__) . "/../KalturaClientBase.php");
require_once(dirname(__FILE__) . "/../KalturaEnums.php");
require_once(dirname(__FILE__) . "/../KalturaTypes.php");

/**
 * @package Kaltura
 * @subpackage Client
 */
class KalturaTag extends KalturaBaseAPI\KalturaObjectBase
{
	/**
	 * 
	 *
	 * @var int
	 * @readonly
	 */
	public $id = null;

	/**
	 * 
	 *
	 * @var string
	 * @readonly
	 */
	public $tag = null;

	/**
	 * 
	 *
	 * @var KalturaTaggedObjectType
	 * @readonly
	 */
	public $taggedObjectType = null;

	/**
	 * 
	 *
	 * @var int
	 * @readonly
	 */
	public $partnerId = null;

	/**
	 * 
	 *
	 * @var int
	 * @readonly
	 */
	public $instanceCount = null;

	/**
	 * 
	 *
	 * @var int
	 * @readonly
	 */
	public $createdAt = null;


}

/**
 * @package Kaltura
 * @subpackage Client
 */
class KalturaTagListResponse extends KalturaBaseAPI\KalturaObjectBase
{
	/**
	 * 
	 *
	 * @var array of KalturaTag
	 * @readonly
	 */
	public $objects;

	/**
	 * 
	 *
	 * @var int
	 * @readonly
	 */
	public $totalCount = null;


}

/**
 * @package Kaltura
 * @subpackage Client
 */
class KalturaTagFilter extends KalturaBaseAPI\KalturaFilter
{
	/**
	 * 
	 *
	 * @var KalturaTaggedObjectType
	 */
	public $objectTypeEqual = null;

	/**
	 * 
	 *
	 * @var string
	 */
	public $tagEqual = null;

	/**
	 * 
	 *
	 * @var string
	 */
	public $tagStartsWith = null;

	/**
	 * 
	 *
	 * @var int
	 */
	public $instanceCountEqual = null;

	/**
	 * 
	 *
	 * @var int
	 */
	public $instanceCountIn = null;


}


/**
 * @package Kaltura
 * @subpackage Client
 */
class KalturaTagService extends KalturaBaseAPI\KalturaServiceBase
{
	function __construct(KalturaBaseAPI\KalturaClient $client = null)
	{
		parent::__construct($client);
	}

	function search(KalturaTagFilter $tagFilter, KalturaBaseAPI\KalturaFilterPager $pager = null)
	{
		$kparams = array();
		$this->client->addParam($kparams, "tagFilter", $tagFilter->toParams());
		if ($pager !== null)
			$this->client->addParam($kparams, "pager", $pager->toParams());
		$this->client->queueServiceActionCall("tagsearch_tag", "search", $kparams);
		if ($this->client->isMultiRequest())
			return $this->client->getMultiRequestResult();
		$resultObject = $this->client->doQueue();
		$this->client->throwExceptionIfError($resultObject);
		$this->client->validateObjectType($resultObject, "KalturaTagListResponse");
		return $resultObject;
	}
}
/**
 * @package Kaltura
 * @subpackage Client
 */
class KalturaTagSearchClientPlugin extends KalturaBaseAPI\KalturaClientPlugin
{
	/**
	 * @var KalturaTagService
	 */
	public $tag = null;

	protected function __construct(KalturaBaseAPI\KalturaClient $client)
	{
		parent::__construct($client);
		$this->tag = new KalturaTagService($client);
	}

	/**
	 * @return KalturaTagSearchClientPlugin
	 */
	public static function get(KalturaBaseAPI\KalturaClient $client)
	{
		return new KalturaTagSearchClientPlugin($client);
	}

	/**
	 * @return array<KalturaBaseAPI\KalturaServiceBase>
	 */
	public function getServices()
	{
		$services = array(
			'tag' => $this->tag,
		);
		return $services;
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return 'tagSearch';
	}
}

}