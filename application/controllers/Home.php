<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

	public function index()
	{
		$this->load->model('DictionaryModel');
		$data['languages'] = $this->DictionaryModel->get_languages();
		$data['records'] = $this->DictionaryModel->baseform_get_term(0,0,null);
		$data['termNames'] = $this->DictionaryModel->get_TermNames();
		$this->load->view('dictionary',$data);
	}

	public function Login()
	{
		$this->load->view('login');
	}

	public function Suggest($termID)
	{
		$this->load->model('DictionaryModel');
		$data['record'] = $this->DictionaryModel->get_SingleTerm($termID);
		$this->load->view('suggest_term',$data);
	}

	public function Logout()
	{
		$this->session->sess_destroy();
		redirect('home/index');
	}

	public function SignUp()
	{
		$this->load->view('sign_up');
	}

	public function login_validation()
	{
		$this->form_validation->set_rules('Username','Username','required');
		$this->form_validation->set_rules('Password','Password','required');
		$this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');

		if ($this->form_validation->run())
        {
        	$username = $this->input->post('Username');
        	$password = $this->input->post('Password');

        	$this->load->model('DictionaryModel');
        	if($this->DictionaryModel->can_login($username,$password))
        	{
        		$session_data = $this->DictionaryModel->user_details($username,$password);
        		$this->session->set_userdata($session_data);

        		$laguage_set['language_set'] = 1;
				$this->session->set_userdata($laguage_set);

        		redirect('home/index');
        	}
        	else
        	{
        		$this->session->set_flashdata('response','Username or Password is Invalid!');
        		redirect('home/Login');
        	}
        }
        else
        {
        	$this->Login();
        }
	}

	public function save_Account()
	{
		$this->form_validation->set_rules('SuggestedBy', 'First name', 'required');
		$this->form_validation->set_rules('Username', 'User name', 'required');
		$this->form_validation->set_rules('Password', 'Password', 'required');
		$this->form_validation->set_rules('PassConf', 'Password Confirmation', 'required|matches[Password]');
		$this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
		$this->load->model('DictionaryModel');
		$data = $this->input->post();

		if ($this->form_validation->run())
        {
        	unset($data['PassConf']);
        	if($this->DictionaryModel->saveRecord($data,'accounts'))
	        {
	           	$this->session->set_flashdata('response','Record successfully saved.');
	        }
	        else
	        {
				$this->session->set_flashdata('response','Record was not saved.');
	        }
        }

        $this->SignUp();
	}

	public function save_suggestedTerm($TermID)
	{
		$this->form_validation->set_rules('SuggestedBy', 'Full name', 'required');
		$this->form_validation->set_rules('Email', 'Email', 'required');
		$this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
		$this->load->model('DictionaryModel');
		$data = $this->input->post();

		if ($this->form_validation->run())
        {
        	if($this->DictionaryModel->saveRecord($data,'suggestedterms'))
	        {
	           	$this->session->set_flashdata('response','Suggestion successfully saved. Please take note that this suggestion is still subject for approval of the admin.');
	        }
	        else
	        {
				$this->session->set_flashdata('response','Suggestion was not saved.');
	        }
        }

        return redirect("home/Suggest/{$TermID}");
	}

	public function Terms()
	{
		$this->load->model('DictionaryModel');
		$searched_item = $this->input->get('search');
		$language_id = $this->input->get('Language');

		if(!empty($searched_item))
		{
			$data['terms'] = $this->DictionaryModel->search_terms($searched_item,$language_id);
			$laguage_set['language_set'] = $language_id;
			$this->session->set_userdata($laguage_set);

			$TermExists = $this->DictionaryModel->termExists($searched_item,$language_id);
			if(!empty($TermExists))
			{
				$data['prompt'] = anchor("home/Translation?baseForm={$TermExists['BaseFormID']}&term={$TermExists['TermID']}&translation=0","<i>Add translation here</i>");
			}
		}
		else
		{
			$data['terms'] = $this->DictionaryModel->get_terms($language_id);
			$laguage_set['language_set'] = $language_id;
			$this->session->set_userdata($laguage_set);
		}
		
		$data['languages'] = $this->DictionaryModel->get_languages();
		$data['termNames'] = $this->DictionaryModel->get_TermNames();
		$data['searched'] = $searched_item;

		$this->load->view('view_terms',$data);
	}

	public function BaseForm()
	{
		$this->load->model('DictionaryModel');
		$baseID = $this->input->get('baseForm');
		$inflectionID = $this->input->get('inflection');
		$data['Inflections'] = $this->DictionaryModel->get_inflections($baseID);
		$data['Base_Names'] =  $this->DictionaryModel->get_BaseNames();
		$data['partofspeech'] = $this->DictionaryModel->get_partofspeech();
		//$data['Baseform'] = $this->DictionaryModel->get_BaseForm($baseID);
		$data['record'] = $this->DictionaryModel->get_Singleinflection($inflectionID);
		//$data['Terms'] = $this->DictionaryModel->get_Terms_forAdd($baseID);
		$this->load->view('add_baseform',$data);
	}

	public function Term()
	{
		$this->load->model('DictionaryModel');
		$baseID = $this->input->get('baseForm');
		$termID = $this->input->get('term');
		
		$data['Terms'] = $this->DictionaryModel->get_Terms_forAdd($baseID);
		$data['Base_Names'] =  $this->DictionaryModel->get_BaseNames();
		$data['baseID'] = $baseID;
		$data['first_TermID'] =  $this->DictionaryModel->get_first_termID($baseID);
		$data['record'] =  $this->DictionaryModel->get_SingleTerm($termID);
		$data['termNames'] = $this->DictionaryModel->get_TermNames();
		$related_terms = $this->DictionaryModel->get_relatedTerms($termID);
		$data['relatedTerms'] = "";
		if(count($related_terms))
		{
			foreach($related_terms as $related)
			{
				$data['relatedTerms'] = $data['relatedTerms'].$related->TermName.',';
			}
		}

		$this->load->view('add_term',$data);
	}

	public function Translation()
	{
		$this->load->model('DictionaryModel');
		$baseID = $this->input->get('baseForm');
		$translationID = $this->input->get('translation');

		$data['Terms'] = $this->DictionaryModel->get_Terms_forAdd($baseID);
		$data['languages'] = $this->DictionaryModel->get_languages();
		$data['Base_Names'] =  $this->DictionaryModel->get_BaseNames();
		$data['Translations'] = $this->DictionaryModel->get_translations($this->input->get('term'));
		$data['first_TermID'] =  $this->DictionaryModel->get_first_termID($baseID);
		$data['record'] =  $this->DictionaryModel->get_Singletranslations($translationID);
		//$data['baseID'] = $baseID;
		$this->load->view('add_translation',$data);
	}

	public function save_BaseName($baseID)
	{
		$this->form_validation->set_rules('BaseName','Base Form','required');
		$this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
		$this->load->model('DictionaryModel');
		$data = $this->input->post();
		$isUpdate = $data['saveBaseName'];
		if ($this->form_validation->run())
        {
        	if($isUpdate)
        	{
        		if($this->DictionaryModel->update_basename($data,$baseID))
	            {
	            	$this->session->set_flashdata('response',$data['BaseName'].' successfully updated.');
	            }
	            else
	            {
					$this->session->set_flashdata('response',$data['BaseName'].' was not updated.');
	            }
	            $baseForm = $baseID;
        	}
        	else
        	{
        		unset($data['saveBaseName']);
				if($this->DictionaryModel->saveRecord($data,'baseform'))
	            {
	            	$this->session->set_flashdata('response',$data['BaseName'].' successfully saved.');
	            }
	            else
	            {
					$this->session->set_flashdata('response',$data['BaseName'].' was not saved.');
	            }
	            $baseForm = $this->DictionaryModel->get_latestID('baseform','BaseFormID');
        	}
        }

        return redirect("home/BaseForm?baseForm={$baseForm}&inflection=0");
	}

	public function save_suggestTranslation($searchedTerm,$translationID)
	{
		$this->form_validation->set_rules('suggested_Translation','Translation','required');
		$this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
		$this->load->model('DictionaryModel');
		$translation = $this->input->post('suggested_Translation');
		$SuggestedBy = $this->input->post('SuggestedBy');
		if ($this->form_validation->run())
        {
			if($this->DictionaryModel->save_suggestedTranslation($searchedTerm,$translationID,$translation,$SuggestedBy))
	        {
	            $this->session->set_flashdata('suggestion_response','Suggestion was successfully saved. Please be advised that this suggestion would be subject for approval. Thank you!');
	        }
	        else
	        {
				$this->session->set_flashdata('suggestion_response','Suggestion was not saved.');
	        }
        }

        return redirect("home/search?search={$searchedTerm}&Language=$translationID");
	}

	public function save_Inflection($baseID,$inflectionID)
	{
		$this->form_validation->set_rules('InflectionName','Inflection','required|is_unique[inflection.InflectionName]');
		$this->form_validation->set_rules('partofspeech','Part of Speech','required');
		$this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
		$this->load->model('DictionaryModel');
		$inflectionName = $this->input->post('InflectionName');
		$partofspeech = $this->input->post('partofspeech');

		if ($this->form_validation->run())
        {
        	if(isset($inflectionID) && $inflectionID == 0)
			{
				if($this->DictionaryModel->save_inflection($inflectionName,$baseID,$partofspeech))
	            {
	            	$this->session->set_flashdata('response','Inflection successfully saved.');
	            }
	            else
	            {
					$this->session->set_flashdata('response','Inflection was not saved.');
	            }
			}
			else
			{
				if($this->DictionaryModel->update_inflection($inflectionName,$inflectionID,$partofspeech))
	            {
	            	$this->session->set_flashdata('response','Inflection successfully updated.');
	            }
	            else
	            {
					$this->session->set_flashdata('response','Inflection was not updated.');
	            }
			}
        	
        }
        
        //$baseForm = $this->DictionaryModel->get_latestID('baseform','BaseFormID');
        return redirect("home/BaseForm?baseForm={$baseID}&inflection={$inflectionID}");
	}

	public function save_Term($baseID,$termID)
	{
		$this->form_validation->set_rules('TermName','Term Name','required');
		$this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
		$this->load->model('DictionaryModel');
		$data = $this->input->post();

		$tags = $this->input->post('RelatedTerms');
		$DeletedTags = $this->input->post('DeletedTerms');
		
			if ($this->form_validation->run())
	        {
	        	if(isset($termID) && $termID == 0)
				{
					if($this->DictionaryModel->save_term($data,$baseID))
		            {
		            	$this->session->set_flashdata('response','Term successfully saved.');
		            }
		            else
		            {
						$this->session->set_flashdata('response','Term was not saved.');
		            }
				}
				else
				{
					if($this->DictionaryModel->update_term($data,$termID))
					{
		            	$this->session->set_flashdata('response','Term successfully updated.');
		            }
		            else
		            {
						$this->session->set_flashdata('response','Term was not updated.');
		            }
				}

				if(isset($DeletedTags))
				{
					$this->DictionaryModel->delete_RelatedTerms($DeletedTags,$termID);
				}

				if(isset($tags))
				{
					$this->DictionaryModel->manage_RelatedTerms($tags,$termID);
				}
	        }
        
        //$baseForm = $this->DictionaryModel->get_latestID('baseform','BaseFormID');
        return redirect("home/Term?baseForm={$baseID}&term={$termID}");
	}

	public function save_Translation($baseID,$termID,$translationID)
	{
		$this->form_validation->set_rules('Translation','Translation','required');
		$this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
		$this->load->model('DictionaryModel');

		$data = $this->input->post();
		if ($this->form_validation->run())
        {
        	if(isset($translationID) && $translationID == 0)
			{
				if($this->DictionaryModel->saveRecord($data,'translation'))
	            {
	            	$this->session->set_flashdata('response','Translation successfully saved.');
	            }
	            else
	            {
					$this->session->set_flashdata('response','Translation was not saved.');
	            }
			}
			else
			{
				if($this->DictionaryModel->update_translation($data,$translationID))
	            {
	            	$this->session->set_flashdata('response','Translation successfully updated.');
	            }
	            else
	            {
					$this->session->set_flashdata('response','Translation was not updated.');
	            }
			}
        }
        
        //$baseForm = $this->DictionaryModel->get_latestID('baseform','BaseFormID');
        return redirect("home/Translation?baseForm={$baseID}&term={$termID}&translation={$translationID}");
	}

	public function search()
	{
		$this->load->model('DictionaryModel');
		$searched_item = $this->input->get('search');
		$language_id = $this->input->get('Language');

		$data['baseName'] = $this->DictionaryModel->search_get_baseform($searched_item,$language_id);
		$baseForm = $data['baseName'];
		$data['languages'] = $this->DictionaryModel->get_languages();
		$data['_record'] = $this->DictionaryModel->get_term($baseForm['BaseFormID'],$language_id,$searched_item);
		$data['records'] = $this->DictionaryModel->baseform_get_term($baseForm['BaseFormID'],$language_id,$searched_item);
		$data['records_more'] = $this->DictionaryModel->baseform_get_term_more($baseForm['BaseFormID'],$language_id);
		$data['termNames'] = $this->DictionaryModel->get_TermNames();
		$data['related_words_ID'] = $this->DictionaryModel->related_words_ID($baseForm['BaseFormID'],$language_id);

		$promptResult = $this->DictionaryModel->prompt($searched_item,$language_id);

		if($promptResult == '1')
		{
			$data['prompt'] = "<h3>No record found!</h3><p><i>The search term you've enter does not exist in our vocabulary.<br>Please try again.</i></p>";
		}
		else if($promptResult == '2')
		{
			$data['prompt'] = "<h3>No translation found!</h3><p><i>Translation for the term you've searched may not be available.<br><a onclick='showHide()' style='cursor:pointer'>suggest translation.</a></i></p>";
		}
		else if($promptResult == '3')
		{
			$url = base_url('index.php/home/BaseForm?baseForm=0&inflection=0');
			$data['prompt'] = "<h3>Sorry, this term has no baseform!</h3><p><i><a id='adminOnly' href='{$url}'>add baseform here.</a></i></p>";
		}

		$laguage_set['language_set'] = $language_id;
		$this->session->set_userdata($laguage_set);

		$this->load->view('dictionary',$data);
	}

	public function get_BaseID()
	{
		$this->load->model('DictionaryModel');
		$baseform = $this->input->post('base');
		$baseID = $this->DictionaryModel->get_BaseID($baseform);

		if(!isset($baseID))
		{
			$baseID=0;
		}
		else
		{
			$BaseName = $this->DictionaryModel->get_BaseForm($baseID);
		}

		return redirect("home/BaseForm?baseForm={$baseID}&inflection=0&baseName={$BaseName}");
	}

	public function delete_term($record_id,$tableName,$fieldName,$isViewTerm,$baseID)
	{
		$data = array('Deleted' => 1, 'DateDeleted' => date("Y-m-d H:i:s"));
		$this->load->model('DictionaryModel');

		if($this->DictionaryModel->deleteRecord($record_id,$data,$tableName,$fieldName))
		{
			$this->session->set_flashdata('response','Record Successfully Deleted.');
		}
		else
		{
			$this->session->set_flashdata('response','Record Not Deleted!');
		}

		if($isViewTerm)
		{
			$redirect = "home/Terms";
		}
		else
		{
			$redirect = "home/Term?baseForm={$baseID}&term=0";
		}
		return redirect($redirect);
	}

	public function delete_translation($record_id)
	{
		$data = array('Deleted' => 1, 'DateDeleted' => date("Y-m-d H:i:s"));
		$this->load->model('DictionaryModel');

		if($this->DictionaryModel->deleteRecord($record_id,$data,'translation','TranslationID'))
		{
			$this->session->set_flashdata('response','Record Successfully Deleted.');
		}
		else
		{
			$this->session->set_flashdata('response','Record Not Deleted!');
		}
		return redirect("home/Translation?baseForm=1&term=1&translation=0");
	}

	public function delete_baseform($record_id,$tableName,$fieldName)
	{
		$data = array('Deleted' => 1, 'DateDeleted' => date("Y-m-d H:i:s"));
		$this->load->model('DictionaryModel');

		if($this->DictionaryModel->deleteRecord($record_id,$data,$tableName,$fieldName))
		{
			$this->session->set_flashdata('response','Record Successfully Deleted.');
		}
		else
		{
			$this->session->set_flashdata('response','Record Not Deleted!');
		}

		$baseForm = $this->DictionaryModel->get_latestID('baseform','BaseFormID');
        return redirect("home/BaseForm?baseForm={$baseForm}&inflection=0");
	}

	public function existing_term_search()
	{
		$this->load->model('DictionaryModel');
		$baseID = $this->input->get('baseForm');
		$searched_item = $this->input->get('search');
		
		$data['Terms'] = $this->DictionaryModel->get_Terms_forAdd($baseID);
		$data['Base_Names'] =  $this->DictionaryModel->get_BaseNames();
		$data['termNames'] = $this->DictionaryModel->get_TermNames();
		$data['Searched_Terms'] = $this->DictionaryModel->search_existing_term($searched_item);

		$this->load->view('existing_term',$data);
	}

	public function Existing_Term()
	{
		$this->load->model('DictionaryModel');
		$baseID = $this->input->get('baseForm');
		
		$data['Terms'] = $this->DictionaryModel->get_Terms_forAdd($baseID);
		$data['Base_Names'] =  $this->DictionaryModel->get_BaseNames();
		$data['termNames'] = $this->DictionaryModel->get_TermNames();

		$this->load->view('existing_term',$data);
	}

	public function add_BaseformHasTerm($termID,$baseID,$searchTerm)
	{
		$this->load->model('DictionaryModel');

		if(!$this->DictionaryModel->isTermhasBaseExist($baseID,$termID))
		{
			$data['FKBaseValueID'] = $baseID;
			$data['FKTermID'] = $termID;
			if($this->DictionaryModel->saveRecord($data,'termhasbaseform'))
		    {
		        $this->session->set_flashdata('response','Record successfully saved.');
		    }
		    else
		    {
				$this->session->set_flashdata('response','Record was not saved.');
			}
		}
		else
		{
			$this->session->set_flashdata('class','text-danger');
			$this->session->set_flashdata('response','Record was not saved. Record already exist!');
		}

		return redirect("home/existing_term_search?search={$searchTerm}&baseForm={$baseID}");
	}

	public function delete_BaseformHasTerm($termID,$baseID,$searchTerm)
	{
		$this->load->model('DictionaryModel');

		$data['FKBaseValueID'] = $baseID;
		$data['FKTermID'] = $termID;

		if($this->DictionaryModel->permanent_del($data,'termhasbaseform'))
	    {
	        $this->session->set_flashdata('response','Record successfully deleted.');
	    }
	    else
	    {
			$this->session->set_flashdata('response','Record was not deleted.');
		}

		return redirect("home/existing_term_search?search={$searchTerm}&baseForm={$baseID}");
	}

}
