import * as v from 'valibot'

export const emailSchema = v.string([v.email('Ingrese un email valido')])
