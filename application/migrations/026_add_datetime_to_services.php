<?php defined('BASEPATH') or exit('No direct script access allowed');

/* ----------------------------------------------------------------------------
 * Easy!Appointments - Open Source Web Scheduler
 *
 * @package     EasyAppointments
 * @author      A.Tselegidis <alextselegidis@gmail.com>
 * @copyright   Copyright (c) 2013 - 2020, Alex Tselegidis
 * @license     http://opensource.org/licenses/GPL-3.0 - GPLv3
 * @link        http://easyappointments.org
 * @since       v1.4.0
 * ---------------------------------------------------------------------------- */

/**
 * Class Migration_Add_datetime_to_services
 *
 * @property CI_DB_query_builder $db
 * @property CI_DB_forge $dbforge
 */
class Migration_Add_datetime_to_services extends CI_Migration {
    /**
     * Upgrade method.
     */
    public function up()
    {

        if ( ! $this->db->field_exists('start_datetime', 'services'))
        {
            $fields = [
                'start_datetime' => [
                    'type' => 'DATETIME',
                    'null' => TRUE,
                    'after' => 'attendants_per_booking'
                ]
            ];

            $this->dbforge->add_column('services', $fields);
        }
        if ( ! $this->db->field_exists('end_datetime', 'services'))
        {
            $fields = [
                'end_datetime' => [
                    'type' => 'DATETIME',
                    'null' => TRUE,
                    'after' => 'start_datetime'
                ]
            ];

            $this->dbforge->add_column('services', $fields);
        }
    }

    /**
     * Downgrade method.
     */
    public function down()
    {
        $this->dbforge->drop_column('services', 'start_datetime');
        $this->dbforge->drop_column('services', 'end_datetime');
    }
}
