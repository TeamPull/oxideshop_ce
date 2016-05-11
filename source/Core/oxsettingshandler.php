<?php
/**
 * This file is part of OXID eShop Community Edition.
 *
 * OXID eShop Community Edition is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * OXID eShop Community Edition is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with OXID eShop Community Edition.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @link      http://www.oxid-esales.com
 * @copyright (C) OXID eSales AG 2003-2016
 * @version   OXID eShop CE
 */

/**
 * Settings handler class.
 */
class oxSettingsHandler extends oxSuperCfg
{
    /**
     * Module type.
     *
     * e.g. 'module' or 'theme'
     *
     * @var string
     */
    protected $sModuleType;

    /**
     * Sets the Module type
     *
     * @param string $sModuleType can be either 'module' or 'theme'
     *
     * @return oxSettingsHandler
     */
    public function setModuleType($sModuleType)
    {
        $this->sModuleType = $sModuleType;

        return $this;
    }

    /**
     * Adds Module or Theme Settings to DB.
     *
     * @param object $oModule Module or Theme Object
     */
    public function run($oModule)
    {
        $this->_addModuleSettings($oModule->getInfo('settings'), $oModule->getId());
    }

    /**
     * Add module settings to database.
     *
     * @param array  $aModuleSettings Module settings array
     * @param string $sModuleId       Module id
     */
    protected function _addModuleSettings($aModuleSettings, $sModuleId)
    {
        $this->_removeNotUsedSettings($aModuleSettings, $sModuleId);
        $oConfig = $this->getConfig();
        $sShopId = $oConfig->getShopId();
        $oDb = oxDb::getDb();

        if (is_array($aModuleSettings)) {
            foreach ($aModuleSettings as $aValue) {
                $sOxId = oxUtilsObject::getInstance()->generateUId();

                $sModule = $this->sModuleType . ':' . $sModuleId;
                $sName = $aValue["name"];
                $sType = $aValue["type"];
                $sValue = is_null($oConfig->getConfigParam($sName)) ? $aValue["value"] : $oConfig->getConfigParam($sName);
                $sGroup = $aValue["group"];

                $sConstraints = "";
                if ($aValue["constraints"]) {
                    $sConstraints = $aValue["constraints"];
                } elseif ($aValue["constrains"]) {
                    $sConstraints = $aValue["constrains"];
                }

                $iPosition = $aValue["position"] ? $aValue["position"] : 1;

                $oConfig->setConfigParam($sName, $sValue);
                $oConfig->saveShopConfVar($sType, $sName, $sValue, $sShopId, $sModule);

                $sDeleteSql = "DELETE FROM `oxconfigdisplay` WHERE OXCFGMODULE=" . $oDb->quote($sModule) . " AND OXCFGVARNAME=" . $oDb->quote($sName);
                $sInsertSql = "INSERT INTO `oxconfigdisplay` (`OXID`, `OXCFGMODULE`, `OXCFGVARNAME`, `OXGROUPING`, `OXVARCONSTRAINT`, `OXPOS`) " .
                "VALUES ('{$sOxId}', " . $oDb->quote($sModule) . ", " . $oDb->quote($sName) . ", " . $oDb->quote($sGroup) . ", " . $oDb->quote($sConstraints) . ", " . $oDb->quote($iPosition) . ")";

                $oDb->execute($sDeleteSql);
                $oDb->execute($sInsertSql);
            }
        }
    }

    /**
     * Removes configs which are removed from module metadata
     *
     * @param array  $aModuleSettings Module settings
     * @param string $sModuleId       Module id
     */
    protected function _removeNotUsedSettings($aModuleSettings, $sModuleId)
    {
        $aModuleConfigs = $this->_getModuleConfigs($sModuleId);
        $aModuleSettings = $this->_parseModuleSettings($aModuleSettings);

        $aConfigsToRemove = array_diff($aModuleConfigs, $aModuleSettings);
        if (!empty($aConfigsToRemove)) {
            $this->_removeModuleConfigs($sModuleId, $aConfigsToRemove);
        }
    }

    /**
     * Returns module configuration from database
     *
     * @param string $sModuleId Module id
     *
     * @return array
     */
    protected function _getModuleConfigs($sModuleId)
    {
        $oDb = oxDb::getDb();
        $sQuotedShopId = $oDb->quote($this->getConfig()->getShopId());
        $sQuotedModuleId = $oDb->quote($this->sModuleType . ':' . $sModuleId);

        $sModuleConfigsQuery = "SELECT oxvarname FROM oxconfig WHERE oxmodule = $sQuotedModuleId AND oxshopid = $sQuotedShopId";

        return $oDb->getCol($sModuleConfigsQuery);
    }

    /**
     * Parses module config variable names to array from module settings
     *
     * @param array $aModuleSettings Module settings
     *
     * @return array
     */
    protected function _parseModuleSettings($aModuleSettings)
    {
        $aSettings = array();

        if (is_array($aModuleSettings)) {
            foreach ($aModuleSettings as $aSetting) {
                $aSettings[] = $aSetting['name'];
            }
        }

        return $aSettings;
    }

    /**
     * Removes module configs from database
     *
     * @param string $sModuleId        Module id
     * @param array  $aConfigsToRemove Configs to remove
     */
    protected function _removeModuleConfigs($sModuleId, $aConfigsToRemove)
    {
        $oDb = oxDb::getDb();
        $sQuotedShopId = $oDb->quote($this->getConfig()->getShopId());
        $sQuotedModuleId = $oDb->quote($this->sModuleType . ':' . $sModuleId);

        $aQuotedConfigsToRemove = array_map(array($oDb, 'quote'), $aConfigsToRemove);
        $sDeleteSql = "DELETE
                       FROM `oxconfig`
                       WHERE oxmodule = $sQuotedModuleId AND
                             oxshopid = $sQuotedShopId AND
                             oxvarname IN (" . implode(", ", $aQuotedConfigsToRemove) . ")";

        $oDb->execute($sDeleteSql);
    }
}
