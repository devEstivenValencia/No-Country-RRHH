'use client'

import { Loader } from '#/components/Loader.component'
import { Password } from '#/components/Password'
import Typography from '#/components/Typography.component'
import { Button, Form, FormControl, FormField, FormItem, FormLabel, FormMessage, Input } from '#/components/ui'
import { APPROUTES } from '#/config/APP.routes'
import { Password as PasswordEntity, passwordSchema } from '#/entities'
import { emailSchema } from '#/schemas/email.schema'
import { valibotResolver } from '@hookform/resolvers/valibot'
import Image from 'next/image'
import Link from 'next/link'
import { useForm } from 'react-hook-form'
import * as v from 'valibot'

const defaultValues = {
	companyName: '',
	phone: '',
	email: '',
	password: '' as PasswordEntity,
	checkbox: ''
}

type RegisterValues = typeof defaultValues

const registerSchema = v.object({
	companyName: v.string(),
	phone: v.string(),
	email: emailSchema,
	password: passwordSchema,
	checkbox: v.boolean()
})

export default function ProfileForm() {
	const form = useForm<RegisterValues>({
		resolver: valibotResolver(registerSchema),
		defaultValues
	})

	const {
		formState: { isSubmitting },
		handleSubmit,
		setValue
	} = form

	async function onSubmit(values: RegisterValues) {
		console.log(values)
	}

	return (
		<section className='md:bg-secondary-300 md:h-screen flex justify-center items-center '>
			<div className='flex md:justify-center md:items-center md:shadow-md md:w-[1156px] md:h-[900px] md:rounded-3xl p-10 md:gap-20 bg-[#ffffff]'>
				<Image
					src='/images/register-image.png'
					alt='Imagen Recursos'
					width={500}
					height={500}
					className='hidden lg:block'
				/>

				<Form {...form}>
					<form onSubmit={handleSubmit(onSubmit)} className='flex flex-col h-fit gap-4'>
						<div>
							<Typography as='h2' className='text-secondary-500 font-bold'>
								Bienvenido/a
							</Typography>
							<Typography as='span'>Regístrese para continuar</Typography>
						</div>
						<FormField
							control={form.control}
							name='companyName'
							render={({ field }) => (
								<>
									<FormItem>
										<FormLabel htmlFor='companyName'>Nombre de la empresa</FormLabel>
										<FormControl>
											<Input type='text' placeholder='Smith & Co. Consulting' {...field} required />
										</FormControl>
										<Typography as='span' className='text-neutro-800 text-sm'>
											Por favor, ingrese el nombre de la empresa a la que pertenece.
										</Typography>
										<FormMessage />
									</FormItem>
								</>
							)}
						/>
						<FormField
							control={form.control}
							name='phone'
							render={({ field }) => (
								<>
									<FormItem>
										<FormLabel htmlFor='phone'>Número de contacto</FormLabel>
										<FormControl>
											<Input type='text' pattern='[0-9+]*' placeholder='+54 911 56854460' {...field} required />
										</FormControl>
										<Typography as='span' className='text-neutro-800  text-sm'>
											Por favor, ingrese el número de contacto de la empresa.
										</Typography>
										<FormMessage />
									</FormItem>
								</>
							)}
						/>
						<FormField
							control={form.control}
							name='email'
							render={({ field }) => (
								<>
									<FormItem>
										<FormLabel htmlFor='email'>Correo electrónico</FormLabel>
										<FormControl>
											<Input type='email' placeholder='ejemplo@gmail.com' {...field} required />
										</FormControl>
										<Typography as='span' className='text-neutro-800 text-sm'>
											Por favor, introduzca su dirección de correo electrónico.
										</Typography>
										<FormMessage />
									</FormItem>
								</>
							)}
						/>
						<FormField
							control={form.control}
							name='password'
							render={field => (
								<FormItem>
									<Password variant='new-password' {...field} onChange={e => setValue('password', e.target.value)} />
									<FormMessage />
								</FormItem>
							)}
						/>
						<FormField
							control={form.control}
							name='checkbox'
							render={({ field }) => (
								<>
									<FormItem className='flex flex-row items-center'>
										<FormControl>
											<Input id='checkbox' className='w-[14] accent-primary-500' type='checkbox' {...field} required />
										</FormControl>
										<FormLabel htmlFor='checkbox' className='text-secondary-500 text-sm font-bold'>
											Aceptar los términos y condiciones.
										</FormLabel>
										<FormMessage />
									</FormItem>
								</>
							)}
						/>
						<div className='text-center'>
							<Button
								disabled={isSubmitting}
								variant='default'
								type='submit'
								className='bg-secondary-500 text-neutro-50 font-bold h-9 md:w-[448px] w-72 shadow-md'
							>
								{isSubmitting ? <Loader /> : 'Registrarse'}
							</Button>
						</div>

						<div className='py-2 flex flex-col'>
							<Typography as='span' className='text-center font-bold'>
								¿Ya tiene una cuenta?
							</Typography>
							<Link href={APPROUTES.LOGIN} className='text-primary-500 text-center font-bold'>
								Iniciar Sesión
							</Link>
						</div>
					</form>
				</Form>
			</div>
		</section>
	)
}
