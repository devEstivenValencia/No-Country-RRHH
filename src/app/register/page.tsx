'use client'

import { Loader } from '#/components/Loader.component'
import { Password } from '#/components/Password'
import Typography from '#/components/Typography.component'
import { Button, Form, FormControl, FormField, FormItem, FormLabel, Input } from '#/components/ui'
import { APPROUTES } from '#/config/APP.routes'
import { Password as PasswordEntity } from '#/entities'
import Image from 'next/image'
import Link from 'next/link'
import { useForm } from 'react-hook-form'

const defaultValues = {
	companyName: '',
	phone: '',
	email: '',
	password: '' as PasswordEntity,
	checkbox: ''
}

type RegisterTypes = typeof defaultValues

export default function ProfileForm() {
	const form = useForm<RegisterTypes>({
		defaultValues
	})

	const {
		formState: { isSubmitting },
		handleSubmit
	} = form

	async function onSubmit() {}

	return (
		<section className='md:bg-secondary-300 md:h-screen flex justify-center items-center '>
			<div className='flex md:justify-center md:items-center md:shadow-md md:w-[1156px] md:h-[900px] md:rounded-3xl p-10 md:gap-20 bg-[#ffffff]'>
				<Image
					src='/images/register-image.png'
					alt='Imagen Recursos'
					width={500}
					height={500}
					className='hidden md:block'
				/>

				<Form {...form}>
					<form onSubmit={handleSubmit(onSubmit)} className='flex flex-col h-fit gap-4'>
						<div>
							<Typography as='h2' className='text-secondary-500 font-bold'>
								Bienvenido/a
							</Typography>
							<Typography as='span'>Registrese para continuar</Typography>
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
										<FormLabel htmlFor='phone'>Número de contácto</FormLabel>
										<FormControl>
											<Input type='number' placeholder='+54 911 56854460' {...field} required />
										</FormControl>
										<Typography as='span' className='text-neutro-800  text-sm'>
											Por favor, ingrese el número de contacto de la empresa.
										</Typography>
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
									</FormItem>
								</>
							)}
						/>
						<FormField
							control={form.control}
							name='password'
							render={() => (
								<FormItem>
									<Password variant='new-password' />
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
											<Input className='w-[14] accent-primary-500' type='checkbox' {...field} required />
										</FormControl>
										<Typography as='span' className='text-secondary-500 text-sm font-bold'>
											Aceptar los términos y condiciones.
										</Typography>
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
