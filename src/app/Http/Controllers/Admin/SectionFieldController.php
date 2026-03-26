<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSectionFieldRequest;
use App\Models\Section;
use App\Models\SectionField;
use Illuminate\Http\Request;

class SectionFieldController extends Controller
{
    public function store(StoreSectionFieldRequest $request)
    {
        $data = $request->validated();
        
        // Asegurar que el orden sea correcto
        if (!isset($data['sort_order'])) {
            $lastOrder = SectionField::where('section_id', $data['section_id'])->max('sort_order') ?? 0;
            $data['sort_order'] = $lastOrder + 1;
        }
        
        $field = SectionField::create($data);
        
        return redirect()
            ->route('admin.sections.fields', $data['section_id'])
            ->with('success', "Campo '{$field->label}' creado exitosamente.");
    }

    public function update(Request $request, SectionField $field)
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'label' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255'],
            'type' => ['required', \Illuminate\Validation\Rule::in([
                'text', 'textarea', 'richtext', 'number', 'boolean',
                'date', 'datetime', 'email', 'url', 'image', 'file',
                'select', 'json', 'repeater', 'gallery'
            ])],
            'is_required' => ['boolean'],
            'default_value' => ['nullable', 'string'],
            'placeholder' => ['nullable', 'string', 'max:255'],
            'help_text' => ['nullable', 'string'],
            'sort_order' => ['integer', 'min:0'],
            'is_visible' => ['boolean'],
            'show_in_list' => ['boolean'],
            'show_in_api' => ['boolean'],
            'column_width' => ['required', \Illuminate\Validation\Rule::in(['full', 'half', 'third'])],
            'options' => ['nullable', 'array'],
            'validation_rules' => ['nullable', 'array'],
        ];
        
        $data = $request->validate($rules);
        
        $field->update($data);
        
        return redirect()
            ->route('admin.sections.fields', $field->section_id)
            ->with('success', "Campo '{$field->label}' actualizado exitosamente.");
    }

    public function destroy(SectionField $field)
    {
        $sectionId = $field->section_id;
        $label = $field->label;
        
        $field->delete();
        
        return redirect()
            ->route('admin.sections.fields', $sectionId)
            ->with('success', "Campo '{$label}' eliminado exitosamente.");
    }

    public function reorder(Request $request, Section $section)
    {
        $request->validate([
            'fields' => ['required', 'array'],
            'fields.*' => ['integer', 'exists:section_fields,id'],
        ]);
        
        foreach ($request->input('fields') as $index => $fieldId) {
            SectionField::where('id', $fieldId)
                ->where('section_id', $section->id)
                ->update(['sort_order' => $index + 1]);
        }
        
        return response()->json(['success' => true]);
    }
}
