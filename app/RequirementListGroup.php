<?php

namespace App;

class RequirementListGroup extends ViewComponent
{
    public function toHtml()
    {
        $data = [
            'Name' => $this->name,
            'Short Description' => $this->short_desc,
            'Document Type' => '<code>'.$this->document_type.'</code>'
        ];

        if ($this->due_at) {
            $data['Due Date'] = '<datetime-formatted value="'.$this->due_at->toIso8601String().'"></datetime-formatted>';
        } elseif($this->upfront) {
            $data['Due Date'] = 'Upfront';
        } else {
            $data['Due Date'] = 'n/a';
        }

        if (request()->segment(1) === 'admin') {
            if (isset($this->rules['roles'])) {
                $data['Apply only to these roles'] = function () {
                    foreach($this->rules['roles'] as $role) {
                        echo '<span class="label label-default">'.teamRole($role).'</span> ';
                    }
                };
            }
            
            if (isset($this->rules['age'])) {
                $data['Apply only to this age and below'] = $this->rules['age'];
            }
        }

        return $data;
    }
}