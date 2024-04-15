'use client'

import { Input, InputProps } from '#/components/ui/input'
import { cn } from '#/lib/utils'
import React, { createContext, useContext, useState } from 'react'
import { minLength, regex, safeParse, string } from 'valibot'

import Icon from '../Icon.component'
import Typography from '../Typography.component'
import { FormControl, FormLabel } from '../ui'
import type { PasswordContextTypes, PasswordProps, PasswordValidations } from './Password.types'

const defaultValidations: PasswordValidations = {
	minLenght: false,
	number: false,
	mayusLetter: false,
	specialCharacter: false
}

const PasswordContext = createContext<PasswordContextTypes>({
	shown: false,
	variant: 'current-password',
	toggleShown: () => {},
	validations: defaultValidations
})

export function Password({ variant, ...props }: PasswordProps) {
	const [shown, setShown] = useState(false)
	const [validations, setValidations] = useState<PasswordValidations>(defaultValidations)

	function toggleShown() {
		setShown(!shown)
	}

	function validateNewPassword(e: React.ChangeEvent<HTMLInputElement>) {
		e.preventDefault()
		const { target } = e
		const { value } = target

		setValidations({
			minLenght: safeParse(string([minLength(8)]), value).success,
			number: safeParse(string([regex(/[0-9]/)]), value).success,
			mayusLetter: safeParse(string([regex(/[A-Z]/)]), value).success,
			specialCharacter: safeParse(string([regex(/[#?!@$%^&*-]/)]), value).success
		})
	}

	function onChange(e: React.ChangeEvent<HTMLInputElement>) {
		if (variant === 'new-password') {
			validateNewPassword(e)
		}

		if (props.onChange) props.onChange(e)
	}

	return (
		<PasswordContext.Provider value={{ variant, shown, toggleShown, validations }}>
			<div className='grid w-full items-center gap-1.5 relative'>
				<FormLabel htmlFor='password'>Contraseña</FormLabel>
				<Password.Input {...props} onChange={onChange} />
				<Typography as='span' className='text-neutro-800  text-sm'>
					Por favor, ingrese su contraseña.
				</Typography>
				{variant === 'new-password' && <Password.Validations />}
			</div>
		</PasswordContext.Provider>
	)
}

Password.Input = function PasswordInput({ onChange, ...props }: InputProps) {
	const { shown, toggleShown, variant } = useContext(PasswordContext)
	return (
		<FormControl>
			<div className='relative w-auto h-fit'>
				<Input
					type={shown ? 'text' : 'password'}
					id='password'
					placeholder='Ejemplo123!'
					onChange={onChange}
					className='md:w-[448px]'
					required
					autoComplete={variant}
					{...props}
				/>
				<button type='button' onClick={toggleShown} className='absolute top-2 right-3'>
					<Icon name={shown ? 'eye' : 'eye-crossed'} />
				</button>
			</div>
		</FormControl>
	)
}

Password.Validations = function PasswordValidations() {
	const { validations } = useContext(PasswordContext)

	function Validation({ isValid, text }: { isValid: boolean; text: string }) {
		return (
			<div className={cn('flex flex-row gap-1.5 items-center text-red-500', isValid && 'text-green-500')}>
				<i className={cn('fi !text-xs !size-3', isValid ? 'fi-sr-check-circle' : 'fi-sr-cross-circle')}></i>
				<Typography as='span' className='text-xs'>
					{text}
				</Typography>
			</div>
		)
	}

	const validationTexts: Record<string, string> = {
		minLenght: 'Min. 8 caracteres',
		specialCharacter: 'Carácter especial [!#@*]',
		mayusLetter: 'Letra mayúscula [A...Z]',
		number: 'Número [0...9]'
	}

	const listOfValidationsValues = Object.values(validations)
	const trueValidations = listOfValidationsValues.filter(validation => validation)
	const trueValidationCount = trueValidations.length

	const validationComponents = Object.entries(validations).map(([key, isValid]) => (
		<Validation key={key} text={validationTexts[key]} isValid={isValid} />
	))

	return (
		<>
			<div className='flex w-full'>
				<div className='bg-red-500 h-1.5 w-full rounded-full'></div>
				<div className={cn('h-1.5 w-full rounded-full', trueValidationCount >= 2 && 'bg-yellow-500')}></div>
				<div className={cn('h-1.5 w-full rounded-full', trueValidationCount === 4 && 'bg-green-500')}></div>
			</div>
			<div className='flex flex-col gap-1.5 p-3 rounded-md shadow'>
				<Typography as='p' className='text-xs'>
					La contraseña debe cumplir los siguientes requisitos:
				</Typography>
				{validationComponents}
			</div>
		</>
	)
}
