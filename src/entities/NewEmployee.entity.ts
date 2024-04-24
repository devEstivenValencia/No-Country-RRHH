import { emailSchema } from '#/schemas/email.schema'
import { phoneSchema } from '#/schemas/phone.schema'
import * as v from 'valibot'

import { credentialsSchema } from './Credentials.entity'
import { Password } from './Password'

export interface NewEmployee {
    // companyId: string
    employee: {
        name: string
        dni: string 
        address: { 
            country: string
            province: string
            city: string
            zipcode: string
        } 
        contact: {
            email: string
            phone: string
        }
        credentials: {
            email: string
            password: Password
        }
        admissionDate: string
        role: string
    }
}

export const newEmployeeSchema = v.object({
    // companyId: v.string(),
    employee: v.object({
        name: v.string(),
        dni: v.string(), 
        address: v.object ({ 
            country: v.string(),
            province: v.string(),
            city: v.string(),
            zipcode: v.string()
        }),
        contact: v.object({
            email: emailSchema,
            phone: phoneSchema
        }),
        credentials: credentialsSchema,
        admissionDate: v.string(),
        role: v.string()
    })
}) satisfies v.BaseSchema<NewEmployee>