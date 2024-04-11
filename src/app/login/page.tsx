'use client'

import { Loader } from '#/components/Loader.component'
import { Password } from '#/components/Password'
import Typography from '#/components/Typography.component'
import { Button, Form, FormControl, FormField, FormItem, FormLabel, FormMessage, Input } from '#/components/ui'
import { APPROUTES } from '#/config/APP.routes'
import { Password as PasswordEntity, passwordSchema } from '#/entities'
import { schemaIsValid } from '#/utils/schemaValidator.util'
import Image from 'next/image'
import Link from 'next/link'
import { useForm } from 'react-hook-form'
import * as v from 'valibot'

const defaultValues = {
	email: '',
	password: '' as PasswordEntity
}

type LoginTypes = typeof defaultValues

const loginSchema = v.object({
	email: v.string([v.email()]),
	password: passwordSchema
})

export default function ProfileForm() {
	const form = useForm<LoginTypes>({
		defaultValues
	})

	const {
		formState: { isSubmitting, errors },
		handleSubmit,
		setError
	} = form

	async function onSubmit(values: LoginTypes) {
		if (!schemaIsValid(loginSchema, values)) {
			setError('root.server', { message: 'Usuario o contraseña invalido' })
		}
		// return promise that resolves after 2 seconds
		return new Promise((resolve, reject) => {
			setTimeout(() => resolve((value: string) => value), 2000)
		})
	}

	return (
		<div className='md:bg-primary-300 md:h-screen flex justify-center items-center h-screen'>
			<div className='flex md:justify-center md:shadow-md md:w-[1156px] md:h-[660px] md:rounded-3xl p-20 md:gap-10 bg-[#ffffff]'>
				<div>
					<Image src='/images/login-image.png' alt='Imagen Recursos' width={500} height={500} />
				</div>

				<div className='flex items-center'>
					<Form {...form}>
						<form onSubmit={handleSubmit(onSubmit)} className='space-y-4'>
							<Typography as='h2' className='text-primary-500 font-bold'>
								Bienvenido/a
							</Typography>
							<Typography as='span'>Inicie sesión para continuar</Typography>
							<FormField
								control={form.control}
								name='email'
								render={({ field }) => (
									<>
										<FormItem className='max-w-sm gap-1.5'>
											<FormLabel htmlFor='email'>Correo electrónico</FormLabel>
											<FormControl>
												<Input
													type='email'
													className='md:w-[448px]'
													placeholder='Mi correo electrónico'
													{...field}
													required
												/>
											</FormControl>
											<Typography as='span' className='text-neutro-800'>
												Introduzca su dirección de correo electrónico
											</Typography>
										</FormItem>
									</>
								)}
							/>
							<FormField
								control={form.control}
								name='password'
								render={({ field }) => (
									<FormItem className='max-w-sm gap-1.5'>
										<Password variant='current-password' {...field} />
									</FormItem>
								)}
							/>
							<div className='text-center'>
								<Button
									disabled={isSubmitting}
									variant='default'
									type='submit'
									className='bg-primary-500 text-neutro-50 font-bold h-9 md:w-[448px] w-72 shadow-md'
								>
									{isSubmitting ? <Loader /> : 'Iniciar sesion'}
								</Button>
							</div>

							{errors?.root?.server && <FormMessage>{errors?.root?.server.message}</FormMessage>}

							<div className='py-2 flex flex-col'>
								<Typography as='span' className='text-center font-bold'>
									¿No tienes una cuenta?
								</Typography>
								<Link href={APPROUTES.REGISTER} className='text-secondary-500 text-center font-bold'>
									Registrate
								</Link>
							</div>
						</form>
					</Form>
				</div>
			</div>
		</div>
	)
}
