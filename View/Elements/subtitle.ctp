<?php
/**
 * Subtitle template
 *   - $spaceName
 *   - $roomNames
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */
?>

<?php $this->start('subtitle'); ?>
	<?php echo $this->Rooms->roomsNavi($activeSpaceId); ?>
<?php $this->end();