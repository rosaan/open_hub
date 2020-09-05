<?php

/**
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the BSD 3-Clause License
 * that is bundled with this package in the file LICENSE.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/BSD-3-Clause
 *
 *
 * @author Malaysian Global Innovation & Creativity Centre Bhd <tech@mymagic.my>
 * @link https://github.com/mymagic/open_hub
 * @copyright 2017-2020 Malaysian Global Innovation & Creativity Centre Bhd and Contributors
 * @license https://opensource.org/licenses/BSD-3-Clause
 */

class Csv
{
	public function importOrgCSV($path_to_csv)
	{
		if (!file_exists($path_to_csv)) {
			echo 'CSV File Does Not Exist';

			return;
		}

		$row = 1;
		if (($handle = fopen($path_to_csv, 'r')) !== false) {
			$i = 0;
			while (($data = fgetcsv($handle, 1000, ',')) !== false) {
				if ($i > 0) {
					$title = $data[3];
					echo "processing $title" . PHP_EOL;
					$text_desc = $data[6];
					$website = $data[10];
					$countryCode = $data[24];

					$organization = Organization::title2obj($title);
					if ($organization === null) {
						$organization = new Organization();
						$organization->title = $title;
					}

					if (empty($organization->text_short_description)) {
						$organization->text_short_description = $text_desc;
					}

					if (empty($organization->url_website)) {
						$organization->url_website = $website;
					}

					if (empty($organization->address_country_code)) {
						$country = Country::model()->find('id=:id', array(':id' => $countryCode));
						$organization->address_country_code = $country->code;
					}
					$organization->is_active = 1;

					$organization->save();
				}
				$i = 1;
			}

			fclose($handle);
		}
	}

	public function importEventOrgCSV($path_to_csv)
	{
		if (!file_exists($path_to_csv)) {
			echo 'CSV File Does Not Exist';

			return;
		}

		$row = 1;
		if (($handle = fopen($path_to_csv, 'r')) !== false) {
			$i = 0;
			while (($data = fgetcsv($handle, 1000, ',')) !== false) {
				if ($i > 0) {
					$title = $data[10];
					$event_code = $data[1];
					$as_role_code = $data[6];

					$organization = Organization::title2obj($title);
					if ($organization === null) {
						echo "$title" . PHP_EOL;
						continue;
					}

					$event = Event::model()->find('t.code=:code', array(':code' => trim($event_code)));

					if ($event == null) {
						echo "$event_code" . PHP_EOL;
						continue;
					}

					$criteria = new CDbCriteria;
					$criteria->condition = "event_id = $event->id AND organization_id = $organization->id";
					$eventOrgs = EventOrganization::model()->findAll($criteria);

					if (sizeof($eventOrgs) == 0) {
						$eventOrg = new EventOrganization();
					} else {
						$eventOrg = $eventOrgs[0];
					}

					if (empty($eventOrg->event_code)) {
						$eventOrg->event_code = $event->code;
					}
					if (empty($eventOrg->event_id)) {
						$eventOrg->event_id = $event->id;
					}
					if (empty($eventOrg->event_vendor_code)) {
						$eventOrg->event_vendor_code = 'manual';
					}
					if (empty($eventOrg->organization_id)) {
						$eventOrg->organization_id = $organization->id;
					}
					if (empty($eventOrg->organization_name)) {
						$eventOrg->organization_name = $organization->title;
					}
					if (empty($eventOrg->as_role_code)) {
						$eventOrg->as_role_code = $as_role_code;
					}

					$eventOrg->save();
				}
				$i = 1;
			}

			fclose($handle);
		}
	}

	public function importOrgStatusCSV($path_to_csv)
	{
		if (!file_exists($path_to_csv)) {
			echo 'CSV File Does Not Exist';

			return;
		}

		$row = 1;

		if (($handle = fopen($path_to_csv, 'r')) !== false) {
			$i = 0;
			while (($data = fgetcsv($handle, 1000, ',')) !== false) {
				if ($i > 0) {
					$title = $data[6];
					$date_reported = $data[1];
					$status = $data[2];
					$source = $data[3];

					$organization = Organization::title2obj($title);
					if ($organization === null) {
						echo "$title" . PHP_EOL;
						continue;
					}

					// Should organization_id be unique??

					$sql = "INSERT INTO organization_status (organization_id, date_reported, status, source) 
					VALUES ($organization->id, $date_reported, '$status', '$source')
					ON DUPLICATE KEY UPDATE
					organization_id= $organization->id, date_reported=$date_reported, status='$status',source='$source'";

					//Yii::app()->db->setActive(true);
					try {
						if (Yii::app()->db->createCommand($sql)->execute()) {
							echo 'Record inserted/updated';
						}
					} catch (Exception $e) {
						echo $e;
						continue;
					}
				}
				$i = 1;
			}

			fclose($handle);
		}
	}

	public function importOrgFundingCSV($path_to_csv)
	{
		if (!file_exists($path_to_csv)) {
			echo 'CSV File Does Not Exist';
			return;
		}

		$row = 1;

		if (($handle = fopen($path_to_csv, 'r')) !== false) {
			$i = 0;
			while (($data = fgetcsv($handle, 1000, ',')) !== false) {
				if ($i > 0) {
					$title = $data[12];
					$date_raised = (empty($data[2]) ? 0 : $data[2]);
					$vc_organization_id = (empty($data[3]) ? 0 : $data[3]);
					$vc_name = $data[4];
					$amount = (empty($data[5]) ? 0 : $data[5]);
					$amount_undisclosed = (empty($data[6]) ? 0 : $data[6]);
					$round_type_code = $data[7];
					$source = $data[8];
					$date_added = time();
					$date_modified = time();

					$organization = Organization::title2obj($title);
					if ($organization === null) {
						echo "$title" . PHP_EOL;
						continue;
					}

					// Should organization_id be unique??

					$sql = "INSERT INTO organization_funding (organization_id, date_raised, vc_organization_id, vc_name, amount_undisclosed, amount, round_type_code,
					source, date_added, date_modified) 
					VALUES ($organization->id, $date_raised, $vc_organization_id, '$vc_name', $amount_undisclosed, $amount, '$round_type_code', '$source', $date_added, $date_modified )";

					Yii::app()->db->setActive(true);
					if (Yii::app()->db->createCommand($sql)->execute()) {
						echo 'Record inserted/updated';
					}
				}
				$i = 1;
			}

			fclose($handle);
		}
	}

	public function importOrgRevenueCSV($path_to_csv)
	{
		if (!file_exists($path_to_csv)) {
			echo 'CSV File Does Not Exist';

			return;
		}
		//organization_revenue.csv
		$row = 1;  //skip first row

		if (($handle = fopen($path_to_csv, 'r')) !== false) {
			$i = 0;
			while (($data = fgetcsv($handle, 1000, ',')) !== false) {
				if ($i > 0) {
					$title = $data[9];
					$year_reported = (empty($data[2]) ? 0 : $data[2]);
					$amount = (empty($data[3]) ? 0 : $data[3]);
					$source = $data[4];
					$date_added = time();
					$date_modified = time();

					$organization = Organization::title2obj($title);
					if ($organization === null) {
						echo "$title" . PHP_EOL;
						continue;
					}

					$sql = "INSERT INTO organization_revenue (organization_id, year_reported, amount, source, date_added, date_modified) 
					VALUES ($organization->id, $year_reported, $amount, '$source', $date_added, $date_modified )";

					Yii::app()->db->setActive(true);
					if (Yii::app()->db->createCommand($sql)->execute()) {
						echo 'Record inserted/updated';
					}
				}
				$i = 1;
			}
			fclose($handle);
		}
	}

	public static function importMagicEventCSV($filename): void
	{
		if (!file_exists(dirname(__DIR__, 2) . '/data/tmp/' . $filename . '.csv')) {
			echo 'CSV File Does Not Exist';
			return;
		}

		$row = 1;

		if (($handle = fopen(dirname(__DIR__, 2) . '/data/tmp/' . $filename . '.csv', 'r')) !== false) {
			$i = 0;
			while (($data = fgetcsv($handle)) !== false) {
				$groupCode = YsUtil::generateUUID();
				$eventCode = YsUtil::generateUUID();
				if ($i > 0) {

					$event_name = $data[0] === 'null' ? null : $data[0];
					$event_group = $data[1] === 'null' ? null : $data[1];
					$event_desc = $data[2] === 'null' ? null : $data[2];
					$event_url = $data[3] === 'null' ? null : $data[3];
					$event_start = $data[4] === 'null' ? null : $data[4];
					$event_end = $data[5] === 'null' ? null : $data[5];
					$event_location = $data[6] === 'null' ? null : $data[6];
					$event_email = $data[7] === 'null' ? null : $data[7];


					if ($event_group) {
						$exist = EventGroup::model()->find('title = :title', array(':title' => $event_group));
						if (!$exist) {
							$group = new EventGroup();
							$group->code = $groupCode;
							$group->title = $event_group;
							$group->save();
						}
					}

					$group = EventGroup::model()->find('title = :title', array(':title' => $event_group));
					$event = new Event();
					$event->code = $eventCode;
					$event->vendor = 'manual';
					$event->event_group_code = $event_group ? $group->code : null;
					$event->title = $event_name;
					$event->text_short_desc = $event_desc;
					$event->url_website = $event_url;
					$event->date_started = $event_start;
					$event->date_ended = $event_end;
					$event->full_address = $event_location;
					$event->email_contact = $event_email;
					$event->is_survey_enabled = 0;
					$event->save(false);
					echo "insert startup " . $event_name . "\n";
				}
				$i = 1;
			}
			fclose($handle);
		}
	}

	public static function importMagicStartupCSV($filename): void
	{

		if (!file_exists(dirname(__DIR__, 2) . '/data/tmp/' . $filename . '.csv')) {
			echo 'CSV File Does Not Exist';
			return;
		}

		$row = 1;

		if (($handle = fopen(dirname(__DIR__, 2) . '/data/tmp/' . $filename . '.csv', 'r')) !== false) {
			$i = 0;
			while (($data = fgetcsv($handle)) !== false) {
				if ($i > 0) {
					$orgCode = YsUtil::generateUUID();
					$startup_name = $data[0] === 'null' ? null : $data[0];
					$startup_oneliner = $data[1] === 'null' ? null : $data[1];
					$startup_desc = $data[2] === 'null' ? null : $data[2];
					$startup_founded = $data[3] === 'null' ? null : $data[3];
					$startup_country = $data[4] === 'null' ? null : $data[4];
					$startup_url = $data[5] === 'null' ? null : $data[5];
					$startup_industry = array(
						$data[6] === 'null' ? null : $data[6],
						$data[7] === 'null' ? null : $data[7],
						$data[8] === 'null' ? null : $data[8],
						$data[9] === 'null' ? null : $data[9],
						$data[10] === 'null' ? null : $data[10]
					);

					$country = null;

					if ($startup_country) {
						$modelCountry = Country::model()->find('printable_name = :name', array(':name' => $startup_country));
						if ($modelCountry)
							$country = $modelCountry->code;
					}

					$org = new Organization();
					$org->code = $orgCode;
					$org->title = $startup_name;
					$org->text_oneliner = $startup_oneliner;
					$org->text_short_description = $startup_desc;
					$org->year_founded = $startup_founded;
					$org->year_founded = $startup_founded;
					$org->url_website = $startup_url;
					$org->address_country_code = $country;
					if ($org->save()) {
						foreach ($startup_industry as $industry) {
							if ($industry) {
								$modelIndustry = Industry::model()->find('title = :title', array(':title' => $industry));
								if ($modelIndustry)
									$org->addIndustry($modelIndustry->id);
							}
						}
					}
					echo "insert startup " . $startup_name . "\n";
				}
				$i = 1;
			}
			fclose($handle);
		}
	}
}
