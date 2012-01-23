<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Disclosapage - Disclosure triangles for MojoMotor's page manipulation tree.
 *
 * @author		Robert Sinton, Digital Fusion
 * @license		Apache License v2.0
 * @copyright	2012 Digital Fusion
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *		http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

class Disclosapage_model extends CI_Model
{
	var $dp_table_name = 'disclosapage';

	# Cache of the disclosure state for the current user.
	var $disclosure_state = array();


	// --------------------------------------------------------------------

	/**
	 * Constructor
	 *
	 * @access	public
	 * @return	void
	 */
	function __construct()
	{
       	parent::__construct();
		$this->load->database();
		$this->__check_db();

		$this->disclosure_state = $this->__load_disclosure_state();
	}

	// --------------------------------------------------------------------

	/**
	 * Load the opened/closed state of all current disclosure triangles for the page manipulation tree from the database into the cache.
	 *
	 * @access	private
	 * @return	array
	 */
	private function __load_disclosure_state()
	{
		$this->__check_db();

		# Try to load the stored disclosure state for the current user.
		$this->db->where('member_id', $this->session->userdata('id'));
		$result = $this->db->get($this->dp_table_name);
		if ($result->num_rows() > 0)
		{
			$result_set = $result->result_array();
			$row = $result_set[0];
			if (isset($row['disclosure_state']))
			{
				return unserialize($row['disclosure_state']);
			}
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Save the opened/closed state of all current disclosure triangles for the page manipulation tree into the database.
	 *
	 * @access	private
	 * @return	void
	 */
	private function __save_disclosure_state()
	{
		$this->__check_db();

		# Save the disclosure state for the current user.
		$this->db->where('member_id', $this->session->userdata('id'));
		$state_record = array('disclosure_state' => serialize($this->disclosure_state));
		$this->db->update($this->dp_table_name, $state_record);
	}

	// --------------------------------------------------------------------

	/**
	 * Save the opened/closed state of a specific disclosure triangle to the cache and also (by saving the state) to the database.
	 *
	 * @access	private
	 * @param	string
	 * @param	string
	 * @return	void
	 */
	public function save_disclosure_state($uri_string, $state)
	{
		$this->disclosure_state[$uri_string] = $state;

		$this->__save_disclosure_state();
	}

	// --------------------------------------------------------------------

	/**
	 * Read the opened/closed state of a specific disclosure triangle from the cache.
	 *
	 * @access	public
	 * @param	string
	 * @return	string
	 */
	public function read_disclosure_state($uri_string)
	{
		if (isset($this->disclosure_state[$uri_string]))
		{
			return $this->disclosure_state[$uri_string];
		}
		else
		{
			return '';
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Create the disclosapage table if it doesn't already exist.
	 *
	 * @access	private
	 * @return	void
	 */
	private function __check_db()
	{

		# Create a new table, if necessary, to hold the disclosure states for each user.
		if ( ! $this->db->table_exists($this->dp_table_name))
		{
			$this->load->dbforge();
			$this->dbforge->add_field('id');			
			$this->dbforge->add_field(
				array(
					'member_id'	=> array('type' => 'int')
				)
			);
			$this->dbforge->add_field(
				array(
					'disclosure_state'	=> array('type' => 'text')
				)
			);
			$this->dbforge->create_table($this->dp_table_name);
		}

		# Create a new settings record if there is none for the current user.
		$this->db->where('member_id', $this->session->userdata('id'));
		$result = $this->db->get($this->dp_table_name);
		if ($result->num_rows() == 0)
		{
			$this->db->insert($this->dp_table_name, array('member_id' => $this->session->userdata('id'), 'disclosure_state' => serialize($this->disclosure_state)));
		}
	}

}

/* End of file disclosapage_model.php */
/* Location: system/mojomotor/third_party/disclosapage/models/disclosapage_model.php */