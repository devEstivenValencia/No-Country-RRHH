'use client'

import { Button, FormControl, FormField, FormItem, FormLabel, Icon, Input, Password, Typography } from "#/components";
import { Dialog, DialogContent, DialogFooter, DialogTitle, DialogTrigger, DialogClose } from "#/components/ui/dialog"
import { Form, useForm } from "react-hook-form";
import * as v from 'valibot'
import { valibotResolver } from "@hookform/resolvers/valibot";
import { FormProvider } from "react-hook-form";
import { emailSchema } from "#/schemas";
import { Password as PasswordEntity, passwordSchema } from '#/entities'

const defaultValues = {
    employeeName: '',
    dni: '',
    country: '',
    state: '',
    city: '',
    phone: '',
    email: '',
    password: '' as PasswordEntity,
    rol: '',
    date: ''
}

const formEmployeeSchema = v.object({
        employeeName: v.string(),
        dni: v.string(),
        country: v.string(),
        state: v.string(),
        city: v.string(),
        phone: v.string(),
        email: emailSchema,
        password: passwordSchema,
        rol: v.string(),
        date: v.date()
      })

type EmployeeValues = typeof defaultValues

export function EmployeeForm () {

    const methods = useForm()

    const handleFormReset = () => { form.reset()}

    const form = useForm<EmployeeValues>({
        resolver: valibotResolver(formEmployeeSchema),
        defaultValues
    })

    function onSubmit(values: EmployeeValues) {
        console.log(values)
    }

    return (
        <Dialog>
            <DialogTrigger asChild className="bg-slate-400">
                <Button variant='outline' className="bg-green-400 hover:bg-green-500 text-neutro-50 md:gap-4 gap-1 ">
                    Agregar
                    <Icon name="add"></Icon>
                </Button>
            </DialogTrigger>
            <DialogContent className="h-full md:h-auto w-full bg-[#FFFFFF] border border-neutro-50 overflow-auto touch-pan-y">
                <FormProvider {...methods}>
                    <Form {...form}>
                        <form onSubmit={form.handleSubmit(onSubmit)} className="md:flex md:justify-between gap-8 ">
                            <div className="flex flex-col justify-between">
                                <div className="flex flex-col gap-3">
                                    <DialogTitle className="text-primary-500 font-bold">Datos personales</DialogTitle>
                                    <FormField 
                                        control={form.control}
                                        name="employeeName"
                                        render={({ field }) => (
                                            <>
                                                <FormItem>
                                                <FormLabel htmlFor='employeeName'>Apellidos y nombres</FormLabel>
                                                <FormControl>
                                                    <Input placeholder="Apellidos y nombres" className="border border-neutro-400" {...field} />
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
                                                    <Input type="number" placeholder="71548692" className="border border-neutro-400"  {...field} />
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
                                                    <Input placeholder="Argentina" className="border border-neutro-400"  {...field} />
                                                </FormControl>
                                                <Typography as="span" className="text-sm text-neutro-800 mb-1">Ingresa el país del empleado</Typography>
                                                </FormItem>
                                            </>
                                        )}
                                    />
                                    <FormField
                                        control={form.control}
                                        name="state"
                                        render={({ field }) => (
                                            <>
                                                <FormItem>
                                                <FormLabel htmlFor='state'>Provincia</FormLabel>
                                                <FormControl>
                                                    <Input placeholder="Córdoba" className="border border-neutro-400"  {...field} />
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
                                                    <Input placeholder="Buenos Aires" className="border border-neutro-400"  {...field} />
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
                                                    <Input type="number" placeholder="+54 115684775680" className="border border-neutro-400"  {...field} />
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
                                                    <Input placeholder="ejemplo@gmail.com" className="border border-neutro-400"  {...field} />
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
                                                    <Input placeholder="ejemplo@gmail.com" className="border border-neutro-400"  {...field} />
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
                                                <Password variant='current-password' className="w-full border border-neutro-400" {...field} />
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
                                            name="rol"
                                            render={({ field }) => (
                                                <>
                                                    <FormItem>
                                                    <FormLabel htmlFor='rol'>Rol</FormLabel>
                                                    <FormControl>
                                                        <Input placeholder="Ingeniero" className="border border-neutro-400"  {...field} />
                                                    </FormControl>
                                                    <Typography as="span" className="text-sm text-neutro-800">Rol del empleado</Typography>
                                                    </FormItem>
                                                </>
                                            )}
                                        />
                                        <FormField 
                                            control={form.control}
                                            name="date"
                                            render={({ field }) => (
                                                <>
                                                    <FormItem>
                                                    <FormLabel htmlFor='date'>Fecha de ingreso</FormLabel>
                                                    <FormControl>
                                                        <Input type="date" placeholder="04/11/2023" className="border border-neutro-400 text-neutro-500 md:w-28 w-full"  {...field} />
                                                    </FormControl>
                                                    <Typography as="span" className="text-sm text-neutro-800">Fecha de ingreso</Typography>
                                                    </FormItem>
                                                </>
                                            )}
                                        /> 
                                    </div>
                                </div>
                            </div>
                        </form>
                    </Form>
                </FormProvider>
                <DialogFooter className="flex flex-row justify-between gap-5">
                    <DialogClose asChild>
                        <Button type="button" variant='secondary' onClick={handleFormReset} className="border border-red-500 text-red-500 shadow-md hover:bg-red-500 hover:text-neutro-50 md:w-[100px] w-full">Cancelar</Button>
                    </DialogClose>
                    <Button type="submit" className="bg-green-400 hover:bg-green-500 text-neutro-50 shadow-md md:w-[100px] w-full">Agregar</Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>
    )
}