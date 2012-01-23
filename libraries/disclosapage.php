<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Disclosapage - Disclosure triangles for MojoMotor's page manipulation tree.
 *
 * @author		Robert Sinton, Digital Fusion
 * @license		Apache License v2.0
 * @copyright	2012 Digital Fusion
 *
 * js() and _load_vew() functions are from Dan Horrigan's "Equipment" addon for MojoMotor.
 * https://github.com/dhorrigan/mojo-equipment
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

class Disclosapage
{
	public $addon_version = '1.1';
	public $display_name = 'Disclosapage';

	private $mojo;

	// --------------------------------------------------------------------

	/**
	 * Constructor
	 *
	 * @access	public
	 * @return	void
	 */
	function __construct()
	{
		$this->mojo =& get_instance();
	}

	// --------------------------------------------------------------------

	/**
	 * Starts up the implementation of disclosure triangles for the page manipulation tree.
	 *
	 * @access	public
	 * @param	array
	 * @return	string
	 */
	public function start($template_data = array())
	{
		# Disclosure is only for admins and editors.
		if ($this->mojo->session->userdata('group_id') > 2)
		{
			return;
		}

		$return = '<script charset="utf-8" type="text/javascript" src="'.site_url('admin/addons/disclosapage/js/disclosapage.js').'"></script>';
		$return .= PHP_EOL.
			'<style type="text/css">' .
				'.disclosure_target { background: url('.site_url('admin/assets/img/arrow_down.png').') no-repeat center left; } '.
				'.disclosure_triangle_right { background: url('.site_url('admin/assets/img/arrow_right.png').') no-repeat center left; } '.
			'</style>';

		$this->mojo->cp->appended_output[] = $return;

		# Load the invisible form that we use to communicate information about disclosure triangle state changes.
		return $this->_load_view('views/disclosapage');
	
		return '';
	}

	// --------------------------------------------------------------------

	/**
	 * Returns an array of all disclosure states known in the database for the current user.
	 *
	 * Called when the page loads, so that it has the info it needs
	 * to set all the disclosure states to what they were last recorded as.
	 *
	 * @access	public
	 * @param	string
	 * @param	string
	 * @return	void
	 */
    public function disclosure_state_ajax()
    {
		$this->mojo->load->model('disclosapage_model');

		$this->mojo->load->library('javascript');
		$disclosure_state = $this->mojo->disclosapage_model->disclosure_state;

		exit($this->mojo->javascript->generate_json(array('disclosure_state' => $disclosure_state)));
	}

	// --------------------------------------------------------------------

	/**
	 * Called when a disclosure triangle is opened or closed, in order to record that state.
	 *
	 * @access	public
	 * @param	string
	 * @param	string
	 * @return	void
	 */
    public function change_state_ajax()
    {
		$this->mojo->load->model('disclosapage_model');

		$page_url	= $this->mojo->input->post('page_url');
		$new_state	= $this->mojo->input->post('new_state');

		# Strip the base url and index.php if it's there, so that they aren't included in our records.
		if (strpos($page_url, site_url().'index.php') === 0)
		{
			# URL uses conventional MojoMotor URL scheme
			$uri_string = str_replace(site_url().'index.php', '', $page_url);
		}
		else
		{
			# URL doesn't use conventional MojoMotor URL scheme: probably has index.php removed.
			$uri_string = str_replace(site_url(), '', $page_url);
		}

		$this->mojo->disclosapage_model->save_disclosure_state($uri_string, $new_state);
	}

	// --------------------------------------------------------------------

	
	/**
	 * This loads a javascript file and outputs it.
	 * You can specify an addional segment for the loader.js
	 * 
	 * Called like this: http://example.com/index.php/addons/disclosapage/js/disclosapage.js
	 *
	 * This function is from Dan Horrigan's Equipment MojoMotor addon.
	 *
	 * @access	public
	 * @return	string	Outputs the file
	 */
	public function js()
	{
		if ($this->mojo->session->userdata('group_id') > 2)
		{
			return;
		}

		$file_name = $this->mojo->uri->segment(5);
		header("Content-Type: text/javascript");
		exit($this->_load_view('javascript/'.$file_name));
	}

	/**
	 * Loads a view.
	 *
	 * This function is from Dan Horrigan's Equipment MojoMotor addon.
	 *
	 * @access	private
	 * @param	string	The view to load MUST include the folder (i.e. views/index)
	 * @param	array	The data for the view
	 * @param	bool	Where to return the results
	 * @return	string	The view contents
	 */
	private function _load_view($view, $data = array(), $return = TRUE)
	{
		$orig_view_path = $this->mojo->load->_ci_view_path;
		$this->mojo->load->_ci_view_path = APPPATH.'third_party/disclosapage/';

		$return = $this->mojo->load->view($view, $data, $return);

		$this->mojo->load->_ci_view_path = $orig_view_path;
		
		return $return;
	}
}


/* End of file disclosapage.php */
/* Location: system/mojomotor/third_party/disclosapage/libraries/disclosapage.php */