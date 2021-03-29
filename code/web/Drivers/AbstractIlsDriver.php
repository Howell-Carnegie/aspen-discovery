<?php

/**
 * Catalog Specific Driver Class
 *
 * This interface class is the definition of the required methods for
 * interacting with the local catalog.
 */
require_once ROOT_DIR . '/Drivers/AbstractDriver.php';

abstract class AbstractIlsDriver extends AbstractDriver
{
	/** @var  AccountProfile $accountProfile */
	public $accountProfile;
	protected $webServiceURL;

	/**
	 * @param AccountProfile $accountProfile
	 */
	public function __construct($accountProfile)
	{
		$this->accountProfile = $accountProfile;
	}

	/**
	 * @param $username
	 * @param $password
	 * @param $validatedViaSSO
	 * @return User|AspenError
	 */
	public abstract function patronLogin($username, $password, $validatedViaSSO);

	/**
	 * Place Item Hold
	 *
	 * This is responsible for both placing item level holds.
	 *
	 * @param User $patron The User to place a hold for
	 * @param string $recordId The id of the bib record
	 * @param string $itemId The id of the item to hold
	 * @param string $pickupBranch The branch where the user wants to pickup the item when available
	 * @param null|string $cancelDate The date to automatically cancel the hold if not filled
	 * @return  mixed               True if successful, false if unsuccessful
	 *                              If an error occurs, return a AspenError
	 * @access  public
	 */
	abstract function placeItemHold($patron, $recordId, $itemId, $pickupBranch, $cancelDate = null);

	abstract function freezeHold($patron, $recordId, $itemToFreezeId, $dateToReactivate);

	abstract function thawHold($patron, $recordId, $itemToThawId);

	abstract function changeHoldPickupLocation(User $patron, $recordId, $itemToUpdateId, $newPickupLocation);

	abstract function updatePatronInfo($patron, $canUpdateContactInfo);

	function updateHomeLibrary(User $patron, string $homeLibraryCode){
		return [
			'success' => false,
			'messages' => ['Cannot update home library with this ILS.']
		];
	}

	public abstract function getFines($patron, $includeMessages = false);

	/**
	 * @return IndexingProfile|null
	 */
	public function getIndexingProfile()
	{
		global $indexingProfiles;
		if (array_key_exists($this->accountProfile->recordSource, $indexingProfiles)) {
			/** @var IndexingProfile $indexingProfile */
			return $indexingProfiles[$this->accountProfile->recordSource];
		} else {
			return null;
		}
	}

	public function getWebServiceURL()
	{
		if (empty($this->webServiceURL)) {
			$webServiceURL = null;
			if (!empty($this->accountProfile->patronApiUrl)) {
				$webServiceURL = trim($this->accountProfile->patronApiUrl);
			} else {
				global $logger;
				$logger->log('No Web Service URL defined in account profile', Logger::LOG_ALERT);
			}
			$this->webServiceURL = rtrim($webServiceURL, '/'); // remove any trailing slash because other functions will add it.
		}
		return $this->webServiceURL;
	}

	public function getVendorOpacUrl()
	{
		global $configArray;

		if ($this->accountProfile && $this->accountProfile->vendorOpacUrl) {
			$host = $this->accountProfile->vendorOpacUrl;
		} else {
			$host = $configArray['Catalog']['url'];
		}

		if (substr($host, -1) == '/') {
			$host = substr($host, 0, -1);
		}
		return $host;
	}

	function showOutstandingFines()
	{
		return false;
	}

	/**
	 * Returns one of three values
	 * - none - No forgot password functionality exists
	 * - emailResetLink - A link to reset the pin is emailed to the user
	 * - emailPin - The pin itself is emailed to the user
	 * @return string
	 */
	function getForgotPasswordType()
	{
		return 'none';
	}

	function getEmailResetPinTemplate()
	{
		return 'overrideInDriver';
	}

	function processEmailResetPinForm()
	{
		return [
			'success' => false,
			'error' => 'This functionality is not available in the ILS.',
		];
	}

	function selfRegister()
	{
		return [
			'success' => false,
		];
	}

	function getSelfRegistrationFields()
	{
		return [];
	}

	function hasUsernameField()
	{
		return false;
	}

	function updatePin(/** @noinspection PhpUnusedParameterInspection */ User $user, string $oldPin, string $newPin)
	{
		return ['success' => false, 'message' => 'Can not update PINs, this ILS does not support updating PINs'];
	}

	function hasMaterialsRequestSupport()
	{
		return false;
	}

	function getNewMaterialsRequestForm(User $user)
	{
		return 'not supported';
	}

	/**
	 * @param User $user
	 * @return string[]
	 */
	function processMaterialsRequestForm(/** @noinspection PhpUnusedParameterInspection */ $user)
	{
		return ['success' => false, 'message' => 'Not Implemented'];
	}

	function getMaterialsRequests(/** @noinspection PhpUnusedParameterInspection */ User $user)
	{
		return [];
	}

	function getMaterialsRequestsPage(/** @noinspection PhpUnusedParameterInspection */ User $user)
	{
		return 'not supported';
	}

	function deleteMaterialsRequests(/** @noinspection PhpUnusedParameterInspection */ User $patron)
	{
		return ['success' => false, 'message' => 'Not Implemented'];
	}

	/**
	 * Gets form to update contact information within the ILS.
	 *
	 * @param User $user
	 * @return string|null
	 */
	function getPatronUpdateForm(/** @noinspection PhpUnusedParameterInspection */ User $user)
	{
		return null;
	}

	function getNumMaterialsRequests(User $user)
	{
		return 0;
	}

	function importListsFromIls(User $patron)
	{
		return array(
			'success' => false,
			'errors' => array('Importing Lists has not been implemented for this ILS.'));
	}

	public function getAccountSummary(User $user) : AccountSummary
	{
		require_once ROOT_DIR . '/sys/User/AccountSummary.php';
		$summary = new AccountSummary();
		$summary->userId = $user->id;
		$summary->source = 'ils';
		$summary->numCheckedOut = 0;
		$summary->numOverdue = 0;
		$summary->numAvailableHolds = 0;
		$summary->totalFines = 0;
		$summary->expirationDate = 0;
		return $summary;
	}

	public function showMessagingSettings()
	{
		return false;
	}

	public function getMessagingSettingsTemplate(User $user)
	{
		return null;
	}

	public function processMessagingSettingsForm(User $user)
	{
		return array(
			'success' => false,
			'errors' => array('Notification Settings are not implemented for this ILS'));
	}

	public function bookMaterial($patron, $recordId, $startDate, $startTime, $endDate, $endTime)
	{
		return array('success' => false, 'message' => 'Not Implemented.');
	}

	public function cancelBookedMaterial($patron, $cancelIds)
	{
		return array('success' => false, 'message' => 'Not Implemented.');
	}

	public function cancelAllBookedMaterial($patron)
	{
		return array('success' => false, 'message' => 'Not Implemented.');
	}

	public function getMyBookings(User $patron)
	{
		return [];
	}

	public function placeVolumeHold($patron, $recordId, $volumeId, $pickupBranch)
	{
		return array(
			'success' => false,
			'message' => 'Volume level holds have not been implemented for this ILS.');
	}

	public function completeFinePayment(User $patron, UserPayment $payment)
	{
		return [
			'success' => false,
			'message' => 'This functionality has not been implemented for this ILS'
		];
	}

	public function patronEligibleForHolds(User $patron)
	{
		return [
			'isEligible' => true,
			'message' => '',
			'fineLimitReached' => false,
			'maxPhysicalCheckoutsReached' => false,
			'expiredPatronWhoCannotPlaceHolds' => false
		];
	}

	public function getShowAutoRenewSwitch(User $patron)
	{
		return false;
	}

	public function isAutoRenewalEnabledForUser(User $patron)
	{
		return false;
	}

	public function updateAutoRenewal(User $patron, bool $allowAutoRenewal)
	{
		return [
			'success' => false,
			'message' => 'This functionality has not been implemented for this ILS'
		];
	}

	function getPasswordRecoveryTemplate(){
		return null;
	}

	function processPasswordRecovery(){
		return null;
	}

	function getEmailResetPinResultsTemplate(){
		return null;
	}

	function getPasswordPinValidationRules(){
		return [
			'minLength' => 4,
			'maxLength' => 4,
			'onlyDigitsAllowed' => true,
		];
	}

	public function hasEditableUsername()
	{
		return false;
	}

	public function getEditableUsername(User $user)
	{
		return null;
	}

	public function updateEditableUsername(User $patron, $username)
	{
		return [
			'success' => false,
			'message' => 'This functionality has not been implemented for this ILS'
		];
	}

	public function logout(User $user){
		//Nothing by default
	}

	public function getHoldsReportData($location) {
		return null;
	}

	public function getStudentReportData($location,$showOverdueOnly,$date) {
		return null;
	}

	/**
	 * Loads any contact information that is not stored by Aspen Discovery from the ILS. Updates the user object.
	 *
	 * @param User $user
	 */
	public function loadContactInformation(User $user)
	{
		return;
	}

	public function getILSMessages(User $user)
	{
		return [];
	}
}