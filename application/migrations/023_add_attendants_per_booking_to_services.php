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
 * Class Migration_Add_attendants_per_booking_to_services
 *
 * @property CI_DB_query_builder $db
 * @property CI_DB_forge $dbforge
 */
class Migration_Add_attendants_per_booking_to_services extends CI_Migration {
    /**
     * Upgrade method.
     */
    public function up()
    {

        if ( ! $this->db->field_exists('attendants_per_booking', 'services'))
        {
            $fields = [
                'attendants_per_booking' => [
                    'type' => 'INT',
                    'constraint' => '11',
                    'null' => FALSE,
                    'default' => '1',
                    'after' => 'attendants_number'
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
        $this->dbforge->drop_column('services', 'attendants_per_booking');
    }
}
