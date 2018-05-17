<?php

// Load the Google API PHP Client Library.
require_once 'util/vendor/autoload.php';

class Analytics {

    public $analytics = '';
    public $profile = '';

    function __construct() {
        $this->analytics = $this->initializeAnalytics();
        $this->profile = $this->getFirstProfileId($this->analytics);
    }

    public function initializeAnalytics() {
        $client = new Google_Client();
        $client->setApplicationName("cadiem");
        $client->setAuthConfig(KEY_FILE_LOCATION);
        $client->setScopes(['https://www.googleapis.com/auth/analytics.readonly']);
        $analytics = new Google_Service_Analytics($client);

        return $analytics;
    }

    public function getFirstProfileId($analytics) {
// Get the user's first view (profile) ID.
// Get the list of accounts for the authorized user.
        $accounts = $analytics->management_accounts->listManagementAccounts();

        if (count($accounts->getItems()) > 0) {
            $items = $accounts->getItems();
            $firstAccountId = $items[0]->getId();

// Get the list of properties for the authorized user.
            $properties = $analytics->management_webproperties
                    ->listManagementWebproperties($firstAccountId);

            if (count($properties->getItems()) > 0) {
                $items = $properties->getItems();
                $firstPropertyId = $items[0]->getId();

// Get the list of views (profiles) for the authorized user.
                $profiles = $analytics->management_profiles
                        ->listManagementProfiles($firstAccountId, $firstPropertyId);

                if (count($profiles->getItems()) > 0) {
                    $items = $profiles->getItems();

// Return the first view (profile) ID.
                    return $items[0]->getId();
                } else {
                    throw new Exception('No views (profiles) found for this user.');
                }
            } else {
                throw new Exception('No properties found for this user.');
            }
        } else {
            throw new Exception('No accounts found for this user.');
        }
    }

    public function getPageViews($analytics, $profileId, $startDate, $endDate, $maxResults = 10) {
        $optParams = array(
            'dimensions' => 'ga:pagePath',
            'sort' => '-ga:pageviews',
            'max-results' => $maxResults);
        $data = $analytics->data_ga->get(
                'ga:' . $profileId, $startDate, $endDate, 'ga:pageviews', $optParams);
        return $data['rows'];
    }

    public function getUsuarios($analytics, $profileId, $startDate, $endDate) {
        $optParams = array(
            'sort' => '-ga:users'
        );
        $data = $analytics->data_ga->get(
                'ga:' . $profileId, $startDate, $endDate, 'ga:users,ga:newUsers,ga:sessions', $optParams);
        return $data['rows'];
    }
    
    public function getDispositivos($analytics, $profileId, $startDate, $endDate) {
        $optParams = array(
            'dimensions' => 'ga:deviceCategory'
        );
        $data = $analytics->data_ga->get(
                'ga:' . $profileId, $startDate, $endDate, 'ga:users', $optParams);
        return $data['rows'];
    }
    
    public function getPaginasSesion($analytics, $profileId, $startDate, $endDate) {
        $optParams = array();
        $data = $analytics->data_ga->get(
                'ga:' . $profileId, $startDate, $endDate, 'ga:pageviewsPerSession', $optParams);
        return $data['rows'];
    }

}
