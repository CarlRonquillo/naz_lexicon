<?php 
	class DictionaryModel extends CI_Model
	{
		public function get_BaseForm($BaseformID)
		{
			$query = $this->db->get_where('baseform', array('BaseFormID' => $BaseformID));
			return $query->row()->BaseName;
		}

		public function get_BaseID($Baseform)
		{
			$query = $this->db->get_where('baseform', array('BaseName' => $Baseform, 'Deleted' => 0));
			return $query->row()->BaseFormID;
		}

		public function get_TermNames()
		{
			$this->db->distinct();
			$this->db->select('TermID,TermName');
			$this->db->from('term');
			$this->db->where('Deleted',0);
			$query = $this->db->get();
			return $query->result_array();
		}

		public function get_relatedTerms($termID)
		{
			$this->db->select('*');
			$this->db->from('term');
			$this->db->join('termrelatestoterm', 'termrelatestoterm.FKTermIDChild = term.TermID');
			$this->db->order_by('TermName', 'ASC');
			$this->db->where('term.Deleted',0);
			$this->db->where('termrelatestoterm.FKTermIDParent',$termID);
			$query = $this->db->get();
			return $query->result();
		}

		public function get_BaseNames()
		{
			$this->db->distinct();
			$this->db->select('BaseName,BaseFormID');
			$this->db->from('baseform');
			$this->db->order_by('BaseName', 'ASC');
			$this->db->where('Deleted',0);
			$query = $this->db->get();
			return $query->result();
		}

		public function get_languages()
		{
			$this->db->distinct();
			$this->db->select('*');
			$query = $this->db->get('languages');
			return $query->result();
		}

		public function search_get_baseform($Searched_term,$languageID)
		{
			$this->db->select('baseform.BaseName,baseform.BaseFormID');
			$this->db->from('term');
			$this->db->join('termhasbaseform', 'termhasbaseform.FKTermID = term.TermID');
			$this->db->join('baseform', 'baseform.BaseFormID = termhasbaseform.FKBaseValueID');
			$this->db->join('basehasinflection', 'basehasinflection.FKBaseValueID = baseform.BaseFormID','left');
			$this->db->join('inflection', 'inflection.InflectionID= basehasinflection.FKInflectionID','left');
			$this->db->join('translation', 'translation.FKTermID = term.TermID','left');
			$this->db->join('languages', 'languages.LanguageID = translation.FKLanguageID','inner');
			$this->db->where('term.TermName',$Searched_term);
			$this->db->or_where('inflection.InflectionName',$Searched_term);
			$this->db->where('languages.LanguageID', $languageID);
			$this->db->where('term.Deleted', 0);
			$query = $this->db->get();
			if($query->num_rows() > 0)
			{
				return $query->row_array();
			}
		}

		public function baseform_get_term($baseFormID,$languageID)
		{
			$this->db->select('*');
			$this->db->limit(6);
			$this->db->from('baseform');
			$this->db->join('termhasbaseform', 'termhasbaseform.FKBaseValueID = baseform.BaseFormID');
			$this->db->join('term', 'term.TermID = termhasbaseform.FKTermID');
			$this->db->join('essay', 'term.TermID = essay.FKTermID','left');
			$this->db->join('context', 'context.FKTermID = term.TermID','left');
			$this->db->join('translation', 'translation.FKTermID = term.TermID','left');
			$this->db->join('languages', 'languages.LanguageID = translation.FKLanguageID','inner');
			$this->db->where('baseform.BaseFormID',$baseFormID);
			$this->db->where('languages.LanguageID', $languageID);
			$this->db->where('baseform.Deleted', 0);
			$query = $this->db->get();
			if($query->num_rows() > 0)
			{
				return $query->result();
			}
		}

		public function prompt($word,$languageID)
		{
			$this->db->where('TermName',$word);
		    $query1 = $this->db->get('term');
		    if ($query1->num_rows() > 0)
		    {
		    	$termID = $query1->row()->TermID;

		    	if(isset($termID))
			    {
			    	$this->db->where('FKLanguageID',$languageID)->where('FKTermID',$termID);
				    $query = $this->db->get('translation');
				    if ($query->num_rows() > 0)
				    {
				    	$this->db->where('FKTermID',$termID);
					    $query = $this->db->get('termhasbaseform');
					    if ($query->num_rows() <= 0)
					    {
					        return '3';
					    }
				    }
				    else
				    {
				    	return '2';
				    }
			    }
		    }
		    else
		    {
		    	return '1';
		    }
		}

		public function baseform_get_term_more($baseFormID,$languageID)
		{
			$this->db->select('*');
			$this->db->limit($this->row_count('term'),6);
			$this->db->from('baseform');
			$this->db->join('termhasbaseform', 'termhasbaseform.FKBaseValueID = baseform.BaseFormID');
			$this->db->join('term', 'term.TermID = termhasbaseform.FKTermID');
			$this->db->join('essay', 'term.TermID = essay.FKTermID','left');
			$this->db->join('context', 'context.FKTermID = term.TermID','left');
			$this->db->join('translation', 'translation.FKTermID = term.TermID','left');
			$this->db->join('languages', 'languages.LanguageID = translation.FKLanguageID','inner');
			$this->db->where('baseform.BaseFormID',$baseFormID);
			$this->db->where('languages.LanguageID', $languageID);
			$this->db->where('baseform.Deleted', 0);
			$query = $this->db->get();
			if($query->num_rows() > 0)
			{
				return $query->result();
			}
		}

		public function related_words_ID($baseFormID,$languageID)
		{
			$this->db->select('TermID');
			$this->db->from('baseform');
			$this->db->join('termhasbaseform', 'termhasbaseform.FKBaseValueID = baseform.BaseFormID');
			$this->db->join('term', 'term.TermID = termhasbaseform.FKTermID');
			$this->db->join('essay', 'term.TermID = essay.FKTermID','left');
			$this->db->join('translation', 'translation.FKTermID = term.TermID','left');
			$this->db->join('languages', 'languages.LanguageID = translation.FKLanguageID','inner');
			$this->db->where('baseform.BaseFormID',$baseFormID);
			$this->db->where('languages.LanguageID', $languageID);
			$this->db->where('baseform.Deleted', 0);
			$subQuery  =  $this->db->get_compiled_select();

			$this->db->select('FKTermIDChild')->from('termrelatestoterm');
			$this->db->join('translation', 'translation.FKTermID = termrelatestoterm.FKTermIDChild AND translation.FKLanguageID = '.$languageID);
			$this->db->where('FKTermIDParent IN ('.$subQuery.')', NULL, FALSE);
			$subQuery2  =  $this->db->get_compiled_select();

			$this->db->select('TermName,TermID');
			$this->db->from('term');
			$this->db->where('TermID IN ('.$subQuery2.')', NULL, FALSE);
			$query = $this->db->get();
			if($query->num_rows() > 0)
			{
				return $query->result_array();
			}
		}

		public function get_Terms_forAdd($baseFormID)
		{
			$this->db->select('*');
			$this->db->from('baseform');
			$this->db->join('termhasbaseform', 'termhasbaseform.FKBaseValueID = baseform.BaseFormID');
			$this->db->join('term', 'term.TermID = termhasbaseform.FKTermID');
			$this->db->join('essay', 'term.TermID = essay.FKTermID','left');
			$this->db->join('context', 'context.FKTermID = term.TermID','left');
			$this->db->where('baseform.BaseFormID',$baseFormID);
			$this->db->where('baseform.Deleted', 0);
			$this->db->where('term.Deleted', 0);
			$query = $this->db->get();
			if($query->num_rows() > 0)
			{
				return $query->result();
			}
		}

		public function get_SingleTerm($termID)
		{
			$this->db->select('*');
			$this->db->from('term');
			$this->db->join('essay', 'term.TermID = essay.FKTermID','left');
			$this->db->join('context', 'context.FKTermID = term.TermID','left');
			$this->db->where('term.TermID',$termID);
			$this->db->where('term.Deleted', 0);
			$query = $this->db->get();
			if($query->num_rows() > 0)
			{
				return $query->row();
			}
		}

		public function get_TermID($termName)
		{
			$this->db->select('TermID');
			$this->db->from('term');
			$this->db->where('term.TermName',$termName);
			$this->db->where('term.Deleted', 0);
			$query = $this->db->get();
			if($query->num_rows() > 0)
			{
				return $query->row()->TermID;
			}
		}

		public function row_count($tableName)
		{
			$query = $this->db->get($tableName);
			return $query->num_rows();
		}

		public function get_terms()
		{
			$this->db->select('*');
			$this->db->from('term');
			$this->db->join('termhasbaseform', 'termhasbaseform.FKTermID = term.TermID','left');
			$this->db->join('baseform', 'baseform.BaseFormID = termhasbaseform.FKBaseValueID','left');
			$this->db->join('essay', 'term.TermID = essay.FKTermID','left');
			$this->db->join('context', 'context.FKTermID = term.TermID','left');
			$this->db->where('term.Deleted','False');
			$this->db->order_by('TermName', 'ASC');
			$query = $this->db->get();
			if($query->num_rows() > 0)
			{
				return $query->result();
			}
		}

		public function search_terms($searched_item)
		{
			$this->db->select('*');
			$this->db->from('term');
			$this->db->join('termhasbaseform', 'termhasbaseform.FKTermID = term.TermID','left');
			$this->db->join('baseform', 'baseform.BaseFormID = termhasbaseform.FKBaseValueID','left');
			$this->db->join('essay', 'term.TermID = essay.FKTermID','left');
			$this->db->join('context', 'context.FKTermID = term.TermID','left');
			$this->db->where('term.Deleted','False');
			$this->db->like('term.TermName',$searched_item);
			$this->db->or_like('essay.DocumentReference',$searched_item);
			$this->db->or_like('baseform.BaseName',$searched_item);
			$this->db->order_by('TermName', 'ASC');
			$query = $this->db->get();
			if($query->num_rows() > 0)
			{
				return $query->result();
			}
		}

		public function get_first_termID($baseID)
		{
			$this->db->select('TermID');
			$this->db->from('term');
			$this->db->join('termhasbaseform', 'termhasbaseform.FKTermID = term.TermID','left');
			$this->db->join('baseform', 'baseform.BaseFormID = termhasbaseform.FKBaseValueID','left');
			$this->db->where('baseform.BaseFormID',$baseID);
			$this->db->where('term.Deleted','False');
			$query = $this->db->get();
			if($query->num_rows() > 0)
			{
				return $query->row()->TermID;
			}
		}

		public function saveRecord($data,$tableName)
		{
			return $this->db->insert($tableName,$data);
		}

		public function save_inflection($inflectionName,$baseID,$partOfSpeech)
		{
			$data = array('InflectionName' => $inflectionName);
			$this->db->insert('inflection',$data);

			$data = array('PartOfSpeechID' => $partOfSpeech,'FKBaseValueID' => $baseID, 'FKInflectionID' => $this->db->insert_id());
			return $this->db->insert('basehasinflection',$data);
		}

		public function manage_RelatedTerms($tags,$termID)
		{
			$arr = explode(',', $tags);
			foreach ($arr as $tag)
			{
				$FKTermIDChild = $this->get_TermID($tag);
				if(isset($FKTermIDChild))
				{
					$query = $this->db->query("SELECT TRUE FROM termrelatestoterm WHERE FKTermIDParent = ".$termID." AND FKTermIDChild = ".$FKTermIDChild." limit 1");
					$data = array('FKTermIDChild' => $FKTermIDChild, 'FKTermIDParent' => $termID);
					$query->num_rows() == 0 ? $this->db->insert('termrelatestoterm', $data) : false;
				}
			}
		}

		public function delete_RelatedTerms($tags,$termID)
		{
			$arr = explode(',', $tags);
			foreach ($arr as $tag)
			{
				$FKTermIDChild = $this->get_TermID($tag);
				if(isset($FKTermIDChild))
				{
					$query = $this->db->query("SELECT TRUE FROM termrelatestoterm WHERE FKTermIDParent = ".$termID." AND FKTermIDChild = ".$FKTermIDChild." limit 1");
					$data = array('FKTermIDChild' => $FKTermIDChild, 'FKTermIDParent' => $termID);
					$query->num_rows() == 1 ? $this->db->delete('termrelatestoterm', array('FKTermIDParent' => $termID, 'FKTermIDChild' => $FKTermIDChild))  : false;
				}
			}
		}

		public function save_term($data,$baseID)
		{
			$term = array('TermName' => $data['TermName'],'GlossaryEntry' => $data['GlossaryEntry'],'CommonUsage' => $data['CommonUsage']);
			$this->db->insert('term',$term);

			$TermID = $this->db->insert_id();

			$termhasbaseform = array('FKBaseValueID' => $baseID,'FKTermID' => $TermID);
			$this->db->insert('termhasbaseform',$termhasbaseform);

			$essay = array('Title' => $data['Title'],'DocumentReference' => $data['DocumentReference'], 'FKTermID' => $TermID, 'Note' => $data['Note']);
			$this->db->insert('essay',$essay);

			$context = array('FKTermID' => $TermID,'ContextValue' => $data['ContextValue']);
			return $this->db->insert('context',$context);
		}

		public function save_suggestedTranslation($searchedTerm,$translationID,$translation)
		{
			$TermID = '';
			$this->db->select('TermID');
			$this->db->from('Term');
			$this->db->where('TermName', $searchedTerm);
			$query = $this->db->get();
			if($query->num_rows() > 0)
			{
				$TermID = $query->row()->TermID;
			}

			if(isset($TermID))
			{
				$suggestion = array('FKTermID' => $TermID,'FKLanguageID' => $translationID,'Translation' => $translation);
				return $this->db->insert('suggestedtranslation',$suggestion);
			}
			else
			{
				return false;
			}
		}

		public function update_term($data,$termID)
		{
			$term = array('TermName' => $data['TermName'],'Coreterm' => $data['CoreTerm'],'GlossaryEntry' => $data['GlossaryEntry'],'CommonUsage' => $data['CommonUsage']);
			$this->db->where('TermID', $termID);
			$this->db->update('term', $term); 

			$essay = array('Title' => $data['Title'],'DocumentReference' => $data['DocumentReference'], 'Note' => $data['Note']);
			$this->db->where('FKTermID', $termID);
			$this->db->update('essay', $essay);

			$context = array('ContextValue' => $data['ContextValue']);
			$this->db->where('FKTermID', $termID);
			return $this->db->update('context', $context);
		}

		public function update_inflection($inflectionName,$inflectionID,$partOfSpeech)
		{
			$inflection = array('InflectionName' => $inflectionName);
			$this->db->where('InflectionID', $inflectionID);
			$this->db->update('inflection', $inflection); 

			$PartOfSpeech = array('PartOfSpeechID' => $partOfSpeech);
			$this->db->where('FKInflectionID', $inflectionID);
			return $this->db->update('basehasinflection', $PartOfSpeech);
		}

		public function update_basename($data,$baseID)
		{
			$base = array('BaseName' => $data['BaseName']);
			$this->db->where('BaseFormID', $baseID);
			return $this->db->update('baseform', $base); 
		}

		public function update_translation($data,$translationID)
		{
			//$translation = array('BaseName' => $data['BaseName']);
			$this->db->where('TranslationID', $translationID);
			return $this->db->update('translation', $data); 
		}

		public function get_latestID($tableName,$IDname)
		{
			$this->db->select_max($IDname);
			$this->db->from($tableName);
			$query = $this->db->get();
			if($query->num_rows() > 0)
			{
				return $query->row()->$IDname;
			}
		}

		public function get_partOfSpeech()
		{
			$this->db->select('*');
			$this->db->from('PartOfSpeech');
			$query = $this->db->get();
			if($query->num_rows() > 0)
			{
				return $query->result();
			}
		}

		public function get_inflections($baseFormID)
		{
			$this->db->select('*');
			$this->db->from('baseform');
			$this->db->join('basehasinflection', 'basehasinflection.FKBaseValueID = baseform.BaseFormID','inner');
			$this->db->join('inflection', 'inflection.InflectionID = basehasinflection.FKInflectionID','inner');
			$this->db->join('PartOfSpeech', 'PartOfSpeech.PartOfSpeechID = basehasinflection.PartOfSpeechID','inner');
			$this->db->where('baseform.BaseFormID', $baseFormID);
			$this->db->order_by('InflectionName', 'ASC');
			$this->db->where('inflection.Deleted',0);
			$query = $this->db->get();
			if($query->num_rows() > 0)
			{
				return $query->result();
			}
		}

		public function get_Singleinflection($inflectionID)
		{
			$this->db->select('*');
			$this->db->from('inflection');
			$this->db->join('basehasinflection','basehasinflection.FKInflectionID = inflection.InflectionID','left');
			$this->db->join('PartOfSpeech', 'PartOfSpeech.PartOfSpeechID = basehasinflection.PartOfSpeechID','inner');
			$this->db->where('inflection.InflectionID', $inflectionID);
			$query = $this->db->get();
			if($query->num_rows() > 0)
			{
				return $query->row();
			}
		}

		public function get_translations($termID)
		{
			$this->db->select('*');
			$this->db->from('translation');
			$this->db->join('languages', 'languages.LanguageID = translation.FKLanguageID','left');
			$this->db->where('FKTermID', $termID);
			$this->db->where('translation.Deleted', 0);
			$this->db->order_by('Translation', 'ASC');
			$query = $this->db->get();
			if($query->num_rows() > 0)
			{
				return $query->result();
			}
		}

		public function get_Singletranslations($translationID)
		{
			$this->db->select('*');
			$this->db->from('translation');
			$this->db->join('languages', 'languages.LanguageID = translation.FKLanguageID','left');
			$this->db->where('translation.Deleted', 0);
			$this->db->where('translation.TranslationID',$translationID);
			$query = $this->db->get();
			if($query->num_rows() > 0)
			{
				return $query->row();
			}
		}

		public function deleteRecord($record_id ,$data,$tableName,$fieldName)
		{
			return $this->db->where($fieldName,$record_id)->update($tableName,$data);
		}
	}

?>