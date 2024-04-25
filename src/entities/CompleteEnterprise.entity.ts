import { emailSchema } from '#/schemas/email.schema'
import { phoneSchema } from '#/schemas/phone.schema'
import * as v from 'valibot'

export interface CompleteEnterprise {
    location: { 
        country: string
        province: string
        city: string
        zipcode: string
        address: string
    } 
    contact: {
        email: string
        phone: string
    }
    role: string
    sector: string
}

export const completeEnterpriseSchema = v.object({

    location: v.object ({ 
        country: v.string(),
        province: v.string(),
        city: v.string(),
        zipcode: v.string(),
        address: v.string()
    }),
    contact: v.object({
        email: emailSchema,
        phone: phoneSchema
    }),
    role: v.string(),
    sector: v.string()

}) satisfies v.BaseSchema<CompleteEnterprise>