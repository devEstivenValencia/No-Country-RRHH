'use client'

import { Input, InputProps } from '#/components/ui/input'
import { Label } from '#/components/ui/label'
import { cn } from '#/lib/utils'
import React, { createContext, useContext, useState } from 'react'
import { minLength, regex, safeParse, string } from 'valibot'

import Typography from '../Typography.component'
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
		} else if (props.onChange) props.onChange(e)
	}

	return (
		<PasswordContext.Provider value={{ variant, shown, toggleShown, validations }}>
			<div className='grid w-full max-w-sm items-center gap-1.5 relative'>
				<Label htmlFor='password' className='font-bold'>
					Contraseña
				</Label>
				<Password.Input {...props} onChange={onChange} />
				<Typography as='span' className='text-neutro-800'>
					Por favor, ingrese su contraseña.
				</Typography>
				{variant === 'new-password' && <Password.Validations />}
			</div>
		</PasswordContext.Provider>
	)
}

Password.Input = function PasswordInput({ onChange, ...props }: InputProps) {
	const { shown, toggleShown } = useContext(PasswordContext)

	return (
		<div className='relative w-auto h-fit'>
			<Input
				{...props}
				type={shown ? 'text' : 'password'}
				id='password'
				placeholder='Contraseña'
				onChange={onChange}
				className='md:w-[448px]'
			/>
			<button type='button' onClick={toggleShown} className='absolute top-2 right-3'>
				<i className={shown ? 'fi fi-sr-eye' : 'fi fi-sr-eye-crossed'} />
			</button>
		</div>
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

	const validationComponents = Object.entries(validations).map(([key, isValid]) => (
		<Validation key={key} text={validationTexts[key]} isValid={isValid} />
	))

	return (
		<div className='flex flex-col gap-1.5 p-3 rounded-md shadow'>
			<Typography as='p' className='text-xs'>
				La contraseña debe cumplir los siguientes requisitos:
			</Typography>
			{validationComponents}
		</div>
	)
}
