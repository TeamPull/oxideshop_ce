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

namespace OxidEsales\Eshop\Core\Module;

use \oxException;

/**
 * Provides a way to get content from module template block file.
 *
 * @internal Do not make a module extension for this class.
 * @see      http://oxidforge.org/en/core-oxid-eshop-classes-must-not-be-extended.html
 */
class ModuleTemplateBlockContentReader
{
    /**
     * Read and return content for template block file.
     * Path to template block file is provided via $pathFormatter.
     * Throw exception if file does not exist or is not readable.
     *
     * @param string $filePath
     *
     * @throws \oxException
     *
     * @return string
     */
    public function getContent($filePath)
    {

        if (!file_exists($filePath)) {
            $exceptionMessage = "Template block file (%s) was not found.";
            throw oxNew('oxException', sprintf($exceptionMessage, $filePath));
        }

        if (!is_readable($filePath)) {
            $exceptionMessage = "Template block file (%s) is not readable.";
            throw oxNew('oxException', sprintf($exceptionMessage, $filePath));
        }

        return file_get_contents($filePath);
    }
}
