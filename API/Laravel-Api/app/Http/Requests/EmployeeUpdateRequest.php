<?php

namespace App\Http\Requests;

use App\Classes\CustomEncrypter;
use App\Models\KeyManager;
use Illuminate\Http\Exceptions\HttpResponseException;

class EmployeeUpdateRequest extends CustomFormRequest
{
    protected function prepareForValidation(): void
    {
        $keyToDecrypt = [
            'company_id',
            'employee' => [
                'employee_id',
                'dni',
                'address' => [
                    'country',
                    'province',
                    'city',
                    'address',
                    'zipcode',
                ],
                'contact' => [
                    'email',
                    'phone'
                ]
            ]
        ];

        $keyManager = KeyManager::find($this->key_id);
        if ($keyManager) {
            $sharedKey = $keyManager->key;

            $dataDecrypted = CustomEncrypter::recurseSpecificSchemaOpenSSL(
                $this->toArray(),
                $keyToDecrypt,
                base64_decode($sharedKey)
            );
            $dataDecrypted['shared_key'] = $sharedKey;
            //end new
            $this->merge($dataDecrypted);
        } else {
            throw new HttpResponseException(
                response()->json([
                    'error' => 'BAD_REQUEST',
                    'message' => 'error con la clave de encriptación'
                ], 400)
            );
        }
        /*  $dataDecrypted = CustomEncrypter::recurseSpecificSchema(
            array(CustomEncrypter::class, 'decryptString'),
            $this->toArray(),
            $keyToDecrypt
        );

        $this->merge($dataDecrypted); */
    }

    public function authorize(): bool
    {
        return $this->user()->tokenCan('update-employee');
    }

    public function rules(): array
    {
        return [
            'company_id' => 'required|string',

            'employee.employee_id' => 'required|string',
            'employee.name' => 'string|nullable',
            'employee.dni' => 'string|nullable',

            'employee.address.country' => 'string|nullable',
            'employee.address.province' => 'string|nullable',
            'employee.address.city' => 'string|nullable',
            'employee.address.address' => 'string|nullable',
            'employee.address.zipcode' => 'string|nullable',

            'employee.contact.email' => 'email|nullable',
            'employee.contact.phone' => 'string|nullable',

            'employee.admission_date' => 'required|date',
            'employee.role' => 'required|string'
        ];
    }

    public function messages(): array
    {
        return [
            'company_id.required' => 'El id de la companía es obligatorio',

            'employee.employee_id.required' => 'El id del empleado es obligatorio',
            'employee.employee_id.string' => 'El id del empleado debe ser texto',
            'employee.name.required' => 'El nombre del empleado es obligatorio',
            'employee.dni.string' => 'El dni del empleado debe ser texto',

            'employee.address.country.string' => 'El país del empleado debe ser texto',
            'employee.address.province.string' => 'La provincia del empleado debe ser texto',
            'employee.address.address.string' => 'La dirección del empleado debe ser texto',
            'employee.address.zipcode.string' => 'El codigo postal del empleado debe ser texto',

            'employee.contact.email.email' => 'El email de contacto ingresado no es un email válido',
            'employee.contact.phone.email' => 'El teléfono del empleado debe ser texto',

            'contact.phone.required' => 'El número de contacto es obligatorio',

            'employee.admission_date.required' => 'La fecha de contratación del empleado es obligatorio',
            'employee.admission_date.date' => 'La fecha de contratación del empleado debe ser una fecha válida',
            'employee.role.required' => 'El rol del empleado es obligatorio',
            'employee.role.string' => 'El rol del empleado debe ser texto',
        ];
    }
}
