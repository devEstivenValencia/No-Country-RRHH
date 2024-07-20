'use client'

import { Button, Form, FormControl, FormField, FormItem, FormLabel, FormMessage, Icon, Input, Loader, Password, Typography } from "#/components";
import { Dialog, DialogContent, DialogFooter, DialogTitle, DialogTrigger, DialogClose } from "#/components/ui/dialog"
import { useForm } from "react-hook-form";
import * as v from 'valibot'
import { valibotResolver } from "@hookform/resolvers/valibot";
import { emailSchema } from "#/schemas";
import { Password as PasswordEntity, passwordSchema } from '#/entities'
import { NewEmployee } from "#/entities/NewEmployee.entity";
import { employeeService } from "#/services/newEmployee.service";
import { sleep } from "#/lib/utils";
import useSecurity from "#/hooks/useSecurity";

const defaultValues = {
    name: '',
    dni: '',
    country: '',
    province: '',
    city: '',
    address: '',
    email: '',
    phone: '',
    password: '' as PasswordEntity,
    admissionDate: '',
    role: ''
}

type EmployeeValues = typeof defaultValues

const formEmployeeSchema = v.object({
        name: v.string(),
        dni: v.string(),
        country: v.string(),
        province: v.string(),
        city: v.string(),
        address: v.string(),
        email: emailSchema,
        phone: v.string(),
        password: passwordSchema,
        admissionDate: v.string(),
        role: v.string()
      })

export function EmployeeForm (props: { reset: Function, open: boolean, setOpen: Function }) {
	const { error, keypairCreated, keypair, publicPemKey } = useSecurity();

    const handleFormReset = () => {form.reset()}

    const form = useForm<EmployeeValues>({
        resolver: valibotResolver(formEmployeeSchema),
        defaultValues
    })

    const {
        formState: { isSubmitting, errors },
        handleSubmit,
        setValue,
        setError
    } = form

    async function onSubmit(values: EmployeeValues) {
		// eslint-disable-next-line no-unmodified-loop-condition
		while(!error && !keypairCreated){
			await sleep(100)
		}
		if(keypairCreated && publicPemKey){
            const newEmployee: NewEmployee = {
                employee: {
                    name: values.name,
                    dni: values.dni, 
                    location: { 
                        country: values.country,
                        province: values.province,
                        city: values.city,
                        address: values.address
                    }, 
                    contact: {
                        email: values.email,
                        phone: values.phone
                    },
                    credentials: {
                        email: values.email,
                        password: values.password
                    },
                    admissionDate: values.admissionDate,
                    role: values.role
                }
            } 
			await employeeService(newEmployee, keypair, publicPemKey )
            .then(() => {
                props.reset()
                handleFormReset()
            })
            .catch(({ message }) => setError('root.server', { message }))
		}else{
			setError('root.server', { 'message': error })
		}
    }

    return (
        <Dialog open={props.open || false} onOpenChange={(open)=>{props.setOpen(open)}}>
            <DialogTrigger asChild className="bg-slate-400">
                <Button variant='outline' className="bg-green-400 hover:bg-green-500 text-neutro-50 md:gap-4 gap-1 ">
                    Agregar
                    <Icon name="add"></Icon>
                </Button>
            </DialogTrigger>
            <DialogContent className="h-full md:max-h-[700px] w-full bg-[#FFFFFF] border border-neutro-50 overflow-auto touch-pan-y">
                <Form {...form}>
                    <form onSubmit={handleSubmit(onSubmit)}>
                        <div className="md:flex md:justify-between gap-8">
                        <div className="flex flex-col justify-between">
                            <div className="flex flex-col gap-3">
                                <DialogTitle className="text-primary-500 font-bold">Datos personales</DialogTitle>
                                <FormField 
                                    control={form.control}
                                    name="name"
                                    render={({ field }) => (
                                        <>
                                            <FormItem>
                                            <FormLabel htmlFor='name'>Apellidos y nombres</FormLabel>
                                            <FormControl>
                                                <Input type="text" placeholder="Apellidos y nombres" className="border border-neutro-400" {...field} required/>
                                            </FormControl>
                                            <Typography as="span" className="text-sm text-neutro-800 mb-1">Apellido y nombre completo del empleado</Typography>
                                            </FormItem>
                                        </>
                                    )}
                                /> 
                                <FormField
                                    control={form.control}
                                    name="dni"
                                    render={({ field }) => (
                                        <>
                                            <FormItem>
                                            <FormLabel htmlFor='dni'>DNI</FormLabel>
                                            <FormControl>
                                                <Input type="number" placeholder="71548692" className="border border-neutro-400"  {...field} required/>
                                            </FormControl>
                                            <Typography as="span" className="text-sm text-neutro-800 mb-5">Ingrese el número de DNI del empleado</Typography>
                                            </FormItem>
                                        </>
                                    )}
                                />
                            </div>
                            <div className="flex flex-col gap-3">
                                <DialogTitle className="text-primary-500 font-bold">Ubicación</DialogTitle>
                                <FormField
                                    control={form.control}
                                    name="country"
                                    render={({ field }) => (
                                        <>
                                            <FormItem>
                                            <FormLabel htmlFor='country'>País de residencia</FormLabel>
                                            <FormControl>
                                                <Input type="text" placeholder="Argentina" className="border border-neutro-400"  {...field} required/>
                                            </FormControl>
                                            <Typography as="span" className="text-sm text-neutro-800 mb-1">Ingresa el país del empleado</Typography>
                                            </FormItem>
                                        </>
                                    )}
                                />
                                <FormField
                                    control={form.control}
                                    name="province"
                                    render={({ field }) => (
                                        <>
                                            <FormItem>
                                            <FormLabel htmlFor='province'>Provincia</FormLabel>
                                            <FormControl>
                                                <Input type="text" placeholder="Córdoba" className="border border-neutro-400"  {...field} required/>
                                            </FormControl>
                                            <Typography as="span" className="text-sm text-neutro-800 mb-1">Ingresa la provincia del empleado</Typography>
                                            </FormItem>
                                        </>
                                    )}
                                />
                                <FormField
                                    control={form.control}
                                    name="city"
                                    render={({ field }) => (
                                        <>
                                            <FormItem>
                                            <FormLabel htmlFor='city'>Ciudad</FormLabel>
                                            <FormControl>
                                                <Input type="text" placeholder="Buenos Aires" className="border border-neutro-400"  {...field} required/>
                                            </FormControl>
                                            <Typography as="span" className="text-sm text-neutro-800 md:mb-0 mb-5">Ingresa la ciudad del empleado</Typography>
                                            </FormItem>
                                        </>
                                    )}
                                />
                                <FormField
                                    control={form.control}
                                    name="address"
                                    render={({ field }) => (
                                        <>
                                            <FormItem>
                                            <FormLabel htmlFor='address'>Código postal</FormLabel>
                                            <FormControl>
                                                <Input type="text" placeholder="B5001" className="border border-neutro-400"  {...field} required/>
                                            </FormControl>
                                            <Typography as="span" className="text-sm text-neutro-800 md:mb-0 mb-5">Ingresa la ciudad del empleado</Typography>
                                            </FormItem>
                                        </>
                                    )}
                                />
                            </div>
                        </div>
                        <div className="flex flex-col gap-5 justify-between">
                            <div className="flex flex-col gap-3">
                                <DialogTitle className="text-primary-500 font-bold">Contacto</DialogTitle>
                                <FormField 
                                    control={form.control}
                                    name="phone"
                                    render={({ field }) => (
                                        <>
                                            <FormItem>
                                            <FormLabel htmlFor='phone'>Número de contacto</FormLabel>
                                            <FormControl>
                                                <Input type="number" placeholder="+54 115684775680" className="border border-neutro-400"  {...field} required/>
                                            </FormControl>
                                            <Typography as="span" className="text-sm text-neutro-800 mb-1">Ingresa el número de contacto del empleado</Typography>
                                            </FormItem>
                                        </>
                                    )}
                                /> 
                                <FormField
                                    control={form.control}
                                    name="email"
                                    render={({ field }) => (
                                        <>
                                            <FormItem>
                                            <FormLabel htmlFor='email'>Correo electrónico</FormLabel>
                                            <FormControl>
                                                <Input type="email" placeholder="ejemplo@gmail.com" className="border border-neutro-400"  {...field} required/>
                                            </FormControl>
                                            <Typography as="span" className="text-sm text-neutro-800">Ingrese el correo electrónico del empleado</Typography>
                                            </FormItem>
                                        </>
                                    )}
                                />
                            </div>
                            <div className="flex flex-col gap-3">
                                <DialogTitle className="text-primary-500 font-bold">Credenciales</DialogTitle>
                                <FormField
                                    control={form.control}
                                    name="email"
                                    render={({ field }) => (
                                        <>
                                            <FormItem>
                                            <FormLabel htmlFor='email'>Correo electrónico</FormLabel>
                                            <FormControl>
                                                <Input type="email" placeholder="ejemplo@gmail.com" className="border border-neutro-400"  {...field} required/>
                                            </FormControl>
                                            <Typography as="span" className="text-sm text-neutro-800 mb-1">Ingrese el correo electrónico del empleado</Typography>
                                            </FormItem>
                                        </>
                                    )}
                                />
                                <FormField 
                                    control={form.control}
                                    name="password"
                                    render={({ field }) => (
                                        <>
                                            <FormItem className="">
                                            <Password variant='new-password' className="w-full border border-neutro-400" {...field} onChange={e => setValue('password', e.target.value)} required/>
                                            </FormItem>
                                        </>
                                    )}
                                /> 
                            </div>
                            <div className="flex flex-col gap-3">
                                <DialogTitle className="text-primary-500 font-bold">Detalles Laborales</DialogTitle>
                                <div className="flex gap-3">
                                        <FormField
                                        control={form.control}
                                        name="role"
                                        render={({ field }) => (
                                            <>
                                                <FormItem>
                                                <FormLabel htmlFor='role'>Rol</FormLabel>
                                                <FormControl>
                                                    <Input type="text" placeholder="Ingeniero" className="border border-neutro-400"  {...field} required/>
                                                </FormControl>
                                                <Typography as="span" className="text-sm text-neutro-800">Rol del empleado</Typography>
                                                </FormItem>
                                            </>
                                        )}
                                    />
                                    <FormField 
                                        control={form.control}
                                        name="admissionDate"
                                        render={({ field }) => (
                                            <>
                                                <FormItem>
                                                <FormLabel htmlFor='admissionDate'>Fecha de ingreso</FormLabel>
                                                <FormControl>
                                                    <Input type="date" className="border border-neutro-400 text-neutro-500 md:w-28 w-full"  {...field} required/>
                                                </FormControl>
                                                <Typography as="span" className="text-sm text-neutro-800">Fecha de ingreso</Typography>
                                                </FormItem>
                                            </>
                                        )}
                                    /> 
                                </div>
                            </div>
                        </div>
                        </div>
                        <DialogFooter className="flex flex-row justify-between gap-5 mt-5">
                            <DialogClose asChild>
                                <Button type="button" variant='secondary' onClick={handleFormReset} className="border border-red-500 text-red-500 shadow-md hover:bg-red-500 hover:text-neutro-50 md:w-[100px] w-full">Cancelar</Button>
                            </DialogClose>
                            <Button
                                disabled={isSubmitting}
                                variant='default'
                                type="submit" className="bg-green-400 hover:bg-green-500 text-neutro-50 shadow-md md:w-[100px] w-full"
                            >
                                {isSubmitting ? <Loader /> : 'Agregar'}
                            </Button>
                        </DialogFooter>

                        {errors?.root?.server && <FormMessage>{errors?.root?.server.message}</FormMessage>}

                    </form>
                </Form>
            </DialogContent>    
        </Dialog>
    )
}