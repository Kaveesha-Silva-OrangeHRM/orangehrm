<?php
/**
 * OrangeHRM is a comprehensive Human Resource Management (HRM) System that captures
 * all the essential functionalities required for any enterprise.
 * Copyright (C) 2006 OrangeHRM Inc., http://www.orangehrm.com
 *
 * OrangeHRM is free software; you can redistribute it and/or modify it under the terms of
 * the GNU General Public License as published by the Free Software Foundation; either
 * version 2 of the License, or (at your option) any later version.
 *
 * OrangeHRM is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
 * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with this program;
 * if not, write to the Free Software Foundation, Inc., 51 Franklin Street, Fifth Floor,
 * Boston, MA  02110-1301, USA
 */

namespace Orangehrm\Rest\Api\User;

use Orangehrm\Rest\Api\Admin\UserAPI;
use Orangehrm\Rest\Api\EndPoint;
use Orangehrm\Rest\Api\Exception\InvalidParamException;
use Orangehrm\Rest\Api\Pim\EmployeePhotoAPI;
use Orangehrm\Rest\Api\Pim\EmployeeSearchAPI;
use Orangehrm\Rest\Api\User\Model\UserModel;
use Orangehrm\Rest\Http\Response;
use sfContext;

class AboutAPI extends EndPoint
{
    private $organizationService;
    private $configService;

    public function getOrganizationService() {
        if (is_null($this->organizationService)) {
            $this->organizationService = new \OrganizationService(new \OrganizationDao());
        }
        return $this->organizationService;
    }

    /**
     * to get confuguration service
     * @return \ConfigService
     */
    public function getConfigService() {
        if (is_null($this->configService)) {
            $this->configService = new \ConfigService();
            $this->configService->setConfigDao(new \ConfigDao());
        }
        return $this->configService;
    }
    public function getSupportedLanguageListFromYML() {
        $languageList = array();
        $languages = \sfYaml::load(\sfConfig::get("sf_plugins_dir") . '/orangehrmAdminPlugin/config/supported_languages.yml');
        foreach ($languages['languages'] as $lang) {
            $languageList[$lang['key']] = $lang['value'];
        }
        return $languageList;
    }

    public function getVersion()
    {
        include_once \sfConfig::get('sf_root_dir') . "/../lib/confs/sysConf.php";
        $sysConf = new \sysConf();
        return $sysConf->getReleaseVersion();
    }

    /**
     * @return Response
     */
    public function getAboutInfo()
    {
        $response = [];
        $tempOrganization = $this->getOrganizationService()->getOrganizationGeneralInformation();
        $response['OrganizationName'] = $tempOrganization->getName();
        $response['OrganizationCountry'] = $tempOrganization->getCountry();
        $response['OrangeHRMVersion'] = $this->getVersion();
        $response['DateFormat'] = $this->getConfigService()->getAdminLocalizationDefaultDateFormat();
        $response['Language'] = $this->getConfigService()->getAdminLocalizationDefaultLanguage();
        return new Response($response);
    }
}
