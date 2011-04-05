<?php
/**
 * CakeForum
 *
 * Developed by Inservio (http://www.inservio.ba)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @filesource
 * @link          http://cakeforum.inservio.ba
 * @package       cakeforum
 * @version       0.1
 * @license       http://www.opensource.org/licenses/mit-license.php The MIT License
 */


	/**
	 * Forum name
	 */

	Configure::write('CfName', 'CakeForum');

	/**
	 * E-mail
	 */

	Configure::write('CfEmail', 'no-reply@inservio.ba');
	
	/**
	 * Forum URL
	 * If you have a domain such as (www.domain.com/forum) CfFolder should be in 'forum'
	 * and empty if it is located in the root of the domain.
	 */	
	
	Configure::write('CfFolder', 'forum');
	
?>