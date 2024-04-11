'use client'

import { Password } from '#/components/Password'
import Typography from '#/components/Typography.component'
import { Button } from '#/components/ui/button'
import { Form, FormControl, FormField, FormItem, FormMessage } from '#/components/ui/form'
import { Input } from '#/components/ui/input'
import { Label } from '#/components/ui/label'
import { zodResolver } from '@hookform/resolvers/zod'
import Image from 'next/image'
import { useForm } from 'react-hook-form'
import { z } from 'zod'

const formSchema = z.object({
	email: z.string().min(3, {
		message: 'error de email ejemplo'
	})
})

export default function ProfileForm() {
	const form = useForm<z.infer<typeof formSchema>>({
		resolver: zodResolver(formSchema),
		defaultValues: {
			email: ''
		}
	})

	function onSubmit(values: z.infer<typeof formSchema>) {
		console.log(values)
	}

	return (
		<div className='md:bg-primary-300 md:h-screen flex justify-center items-center h-screen'>
			<div className='flex md:justify-center md:shadow-md md:w-[1156px] md:h-[660px] md:rounded-3xl p-20 md:gap-10 bg-[#ffffff]'>
				<div>
					<Image src='/images/Imagen-Recursos.png' alt='Imagen Recursos' width={500} height={500} />
				</div>

				<div className='flex items-center'>
					<Form {...form}>
						<form onSubmit={form.handleSubmit(onSubmit)} className='space-y-4'>
							<Typography as='h2' className='text-primary-500 font-bold'>
								Bienvenido/a
							</Typography>
							<Typography as='span'>Inicie sesión para continuar</Typography>
							<FormField
								control={form.control}
								name='email'
								render={({ field }) => (
									<FormItem className='max-w-sm gap-1.5'>
										<Label htmlFor='email' className='font-bold'>
											Correo electrónico
										</Label>
										<FormControl>
											<Input className='md:w-[448px]' placeholder='Mi correo electrónico' {...field} />
										</FormControl>
										<Typography as='span' className='text-neutro-800'>
											Introduzca su dirección de correo electrónico
										</Typography>
										<FormMessage />
									</FormItem>
								)}
							/>
							<Password variant='current-password' />
							<div className='text-center'>
								<Button
									variant={'default'}
									type='submit'
									className='bg-primary-500 text-neutro-50 font-bold h-9 md:w-[448px] w-72 shadow-md'
								>
									Iniciar sesión
								</Button>
							</div>

							<div className='py-2 flex flex-col'>
								<Typography as='span' className='text-center font-bold'>
									¿No tienes una cuenta?
								</Typography>
								<Typography as='span' className='text-secondary-500 text-center font-bold'>
									Registrate
								</Typography>
							</div>
						</form>
					</Form>
				</div>
			</div>
		</div>
	)
}
