<?php

class SharedContent extends DataObject {
	public $__table = 'shared_content';
	public $id;
	public $type;
	public $name;
	public $sharedFrom;
	public $sharedByUserName;
	public $shareDate;
	public $approved;
	public $approvalDate;
	public $approvedBy;
	public $description;
	public $data;

	public static function getObjectStructure($context) {
		return [
			'id' => [
				'property' => 'id',
				'type' => 'label',
				'label' => 'Id',
				'description' => 'The unique id',
			],
			'type' => [
				'property' => 'type',
				'type' => 'label',
				'label' => 'Object Type',
				'description' => 'The Type of content being shared',
			],
			'name' => [
				'property' => 'name',
				'type' => 'text',
				'label' => 'Name',
				'maxLength' => 100,
				'description' => 'The name of the content being shared',
			],
			'description' => [
				'property' => 'description',
				'type' => 'textArea',
				'label' => 'Description',
				'hideInLists' => true,
				'description' => 'A description of the content being shared',
			],
			'sharedFrom' => [
				'property' => 'sharedFrom',
				'type' => 'label',
				'label' => 'Shared From',
				'description' => 'The library who shared the content',
			],
			'sharedByUserName' => [
				'property' => 'sharedByUserName',
				'type' => 'label',
				'label' => 'Shared By',
				'description' => 'The user who shared the content',
			],
			'shareDate' => [
				'property' => 'shareDate',
				'type' => 'timestamp',
				'readOnly' => true,
				'label' => 'Share Date',
				'description' => 'When the content was shared',
			],
			'approved' => [
				'property' => 'approved',
				'type' => 'checkbox',
				'label' => 'Approved?',
				'description' => 'Whether or not the content is approved for use in the community',
				'hideInLists' => true,
				'required' => false,
			],
			'approvalDate' => [
				'property' => 'approvalDate',
				'type' => 'timestamp',
				'readOnly' => true,
				'label' => 'Approval Date',
				'description' => 'When the content was approved for use',
			],
			'approvedBy' => [
				'property' => 'approvedBy',
				'type' => 'label',
				'readOnly' => true,
				'label' => 'Approved By',
				'description' => 'Who approved the content for use',
			],
			'data' => [
				'property' => 'data',
				'type' => 'textArea',
				'label' => 'Description',
				'readOnly' => true,
				'description' => 'The JSON content that was shared',
				'hideInLists' => true
			],
		];
	}
}