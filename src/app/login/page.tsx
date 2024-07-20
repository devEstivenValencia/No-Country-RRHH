'use client'

import { Loader } from '#/components/Loader.component'
import { Password } from '#/components/Password'
import Typography from '#/components/Typography.component'
import { Button, Form, FormControl, FormField, FormItem, FormLabel, FormMessage, Input } from '#/components/ui'
import { APPROUTES } from '#/config/APP.routes'
import useSecurity from '#/hooks/useSecurity'
import { Credentials, credentialsDefaultValues } from '#/entities/Credentials.entity'
import { loginService } from '#/services/login.service'
import Image from 'next/image'
import Link from 'next/link'
import { useRouter } from 'next/navigation'
import { useForm } from 'react-hook-form'

const sleep = ( ms: number ) => new Promise(resolve => setTimeout(resolve, ms));

export default function LoginPage() {
	const { error, keypairCreated, keypair, publicPemKey } = useSecurity();
	const router = useRouter()

	const form = useForm<Credentials>({
		defaultValues: credentialsDefaultValues
	})

	const {
		formState: { isSubmitting, errors },
		setError,
		handleSubmit
	} = form

	async function onSubmit(credentials: Credentials) {
		// eslint-disable-next-line no-unmodified-loop-condition
		while(!error && !keypairCreated){
			await sleep(100)
		}
		if(keypairCreated && publicPemKey){
			await loginService(credentials, keypair, publicPemKey )
				.then(() => {
					const user = JSON.parse(localStorage.getItem('user') || '{}')
					if (user?.type === 'employee'){
						router.push(APPROUTES.WORKER)
					}else{
						router.push(APPROUTES.DASHBOARD)
					}
				})
				.catch(({ message }) => setError('root.server', {message}))
		}else{
			setError('root.server', { 'message': error })
		}
	}

	return (
		<section className='md:bg-primary-300 md:min-h-screen flex justify-center items-center py-4'>
			<div className='flex md:justify-center md:items-center md:shadow-md md:w-[1156px-2rem] md:h-[600px]  md:rounded-3xl p-10 md:gap-20 bg-[#ffffff] md:mx-[2rem]'>
				<figure className='md:w-[50%] md:max-w-[500px]'>
					<Image
						src='/images/login-image.png'
						alt='Imagen Recursos'
						width={500}
						height={500}
						className='hidden md:block'
					/>
				</figure>

				<Form {...form}>
					<form onSubmit={handleSubmit(onSubmit)} className='flex flex-col h-fit gap-4 md:pr-[2-rem] md:w-[50%] md:max-w-[500px]'>
						<div className='w-full'>
							<Typography as='h2' className='text-primary-500 font-bold'>
								Bienvenido/a
							</Typography>
							<Typography as='span'>Inicie sesión para continuar</Typography>
						</div>
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
											Por favor, introduzca su dirección de correo electrónico
										</Typography>
									</FormItem>
								</>
							)}
						/>
						<FormField
							control={form.control}
							name='password'
							render={({ field }) => (
								<FormItem>
									<Password variant='current-password' {...field} className='w-full' />
								</FormItem>
							)}
						/>
						<div className='text-center'>
							<Button
								disabled={isSubmitting}
								variant='default'
								type='submit'
								className='bg-primary-500 text-neutro-50 font-bold h-9 md:w-full md:max-w-[448px] w-full shadow-md '
							>
								{isSubmitting ? <Loader /> : 'Iniciar Sesión'}
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
		</section>
	)
}
