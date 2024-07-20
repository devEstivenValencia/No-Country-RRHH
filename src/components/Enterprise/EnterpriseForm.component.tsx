'use client'

import { APPROUTES } from "#/config/APP.routes";
import { useRouter } from "next/navigation";
import { Card, CardContent, CardFooter, CardHeader, CardTitle } from "#/components/ui/card";
import { Form, FormControl, FormField, FormItem, FormLabel, FormMessage, Input, Loader, Button, Typography} from "#/components";
import { useForm } from "react-hook-form";
import * as v from 'valibot'
import { valibotResolver } from "@hookform/resolvers/valibot";
import { emailSchema } from "#/schemas";
import { CompleteEnterprise } from "#/entities/CompleteEnterprise.entity";
import { completeEnterpriseService } from "#/services/completeEnterprise.service";
import { useState } from "react";
import { sleep } from "#/lib/utils";
import useSecurity from "#/hooks/useSecurity";

const defaultValues = {
    sector: '',
    country: '',
    province: '',
    city: '',
    zipcode: '',
    email: '',
    phone: '',
    address: '',
    role: ''
}

type CompleteEnterpriseValues = typeof defaultValues

const formCompleteEnterpriseSchema = v.object({
    sector: v.string(),
    country: v.string(),
    province: v.string(),
    city: v.string(),
    zipcode: v.string(),
    email: emailSchema,
    phone: v.string(),
    address: v.string(),
    role: v.string()
})

export function EnterpriseForm () { 
	const { error, keypairCreated, keypair, publicPemKey } = useSecurity();

    const router = useRouter()

    const [formClose, setFormClose] = useState(true)
    const handleFormClose = () => setFormClose(!formClose)

    const [monday, setMonday] = useState(true)
    const handleMonday = () => setMonday(!monday)
    const [tuesday, setTuesday] = useState(true)
    const handleTuesday = () => setTuesday(!tuesday)
    const [wednesday, setWednesday] = useState(true)
    const handleWednesday = () => setWednesday(!wednesday)
    const [thursday, setThursday] = useState(true)
    const handleThursday = () => setThursday(!thursday)
    const [friday, setFriday] = useState(true)
    const handleFriday = () => setFriday(!friday)
    const [saturday, setSaturday] = useState(true)
    const handleSaturday = () => setSaturday(!saturday)
    const [sunday, setSunday] = useState(true)
    const handleSunday = () => setSunday(!sunday)

    const form = useForm<CompleteEnterpriseValues>({
        resolver: valibotResolver(formCompleteEnterpriseSchema),
        defaultValues
    })

    const {
        formState: { isSubmitting, errors },
        handleSubmit,
        setError
    } = form

    async function onSubmit(values: CompleteEnterpriseValues) {
        // eslint-disable-next-line no-unmodified-loop-condition
        while(!error && !keypairCreated){
            await sleep(100)
        }

        if(keypairCreated && publicPemKey){
            const completeEnterprise: CompleteEnterprise = {
                location: { 
                    country: values.country,
                    province: values.province,
                    city: values.city,
                    zipcode: values.zipcode,
                    address: values.address
                },
                contact: {
                    email: values.email,
                    phone: values.phone
                },
                role: values.role,
                sector: values.sector
            }
    
            const workingWeek: string[] = []
            if(!monday) workingWeek.push('lu')
            if(!tuesday) workingWeek.push('ma')
            if(!wednesday) workingWeek.push('mi')
            if(!thursday) workingWeek.push('ju')
            if(!friday) workingWeek.push('vi')
            if(!saturday) workingWeek.push('sa')
            if(!sunday) workingWeek.push('do')
            
            await completeEnterpriseService(completeEnterprise, workingWeek, keypair, publicPemKey )
                .then(() => router.push(APPROUTES.DASHBOARD))
                .catch(({ message }) => setError('root.server', {message}))
        }else{
            setError('root.server', { 'message': error })
        }
    }

    return (
        <Card className="z-50 absolute md:w-full md:min-h-screen backdrop-blur-sm rounded-none flex justify-center items-center border border-neutro-50 bg-primary-50">
            <CardContent className="bg-[#FFFFFF] md:max-w-[1000px] md:min-h-[750px] md:rounded-2xl md:shadow-md p-8">
            <CardHeader className="p-0 mb-5">
                <Typography as="span">Por favor, completa la información de configuración antes de comenzar a utilizar el sistema.</Typography>
            </CardHeader>
            <Form {...form}>
                <form onSubmit={handleSubmit(onSubmit)}>
                    <div className="md:flex md:justify-between gap-8">
                        <div className="flex flex-col justify-between">
                            <div className="flex flex-col gap-3">
                                <CardTitle className="text-primary-500 font-bold">Datos de la empresa</CardTitle>
                                <FormField 
                                    control={form.control}
                                    name="role"
                                    render={({ field }) => (
                                        <>
                                            <FormItem>
                                            <FormLabel htmlFor='role'>Roles</FormLabel>
                                            <FormControl>
                                                <Input type="text" placeholder="Contador" className="border border-neutro-400" {...field} required/>
                                            </FormControl>
                                            <Typography as="span" className="text-sm text-neutro-800 mb-1">Ingrese los roles del empleado</Typography>
                                            </FormItem>
                                        </>
                                    )}
                                /> 
                                <FormField
                                    control={form.control}
                                    name="sector"
                                    render={({ field }) => (
                                        <>
                                            <FormItem>
                                            <FormLabel htmlFor='sector'>Sector</FormLabel>
                                            <FormControl>
                                                <Input type="text" placeholder="Minería" className="border border-neutro-400"  {...field} required/>
                                            </FormControl>
                                            <Typography as="span" className="text-sm text-neutro-800 mb-3">Ingrese el sector de la empresa</Typography>
                                            </FormItem>
                                        </>
                                    )}
                                />
                                <Typography as="span" className="text-sm font-semibold">Días laborales</Typography>
                                <div className="flex gap-3">
                                    <div onClick={handleMonday}>
                                        {monday ? <Button type="button" className="p-1 w-8 rounded-full font-normal bg-neutro-200">LU</Button> : <Button type="button" className="p-1 w-8 rounded-full font-normal bg-primary-300">LU</Button>}
                                    </div>
                                    <div onClick={handleTuesday}>
                                        {tuesday ? <Button type="button" className="p-1 w-8 rounded-full font-normal bg-neutro-200">MA</Button> : <Button type="button" className="p-1 w-8 rounded-full font-normal bg-primary-300">MA</Button>}
                                    </div>
                                    <div onClick={handleWednesday}>
                                        {wednesday ? <Button type="button" className="p-1 w-8 rounded-full font-normal bg-neutro-200">MI</Button> : <Button type="button" className="p-1 w-8 rounded-full font-normal bg-primary-300">MI</Button>}
                                    </div>
                                    <div onClick={handleThursday}>
                                        {thursday ? <Button type="button" className="p-1 w-8 rounded-full font-normal bg-neutro-200">JU</Button> : <Button type="button" className="p-1 w-8 rounded-full font-normal bg-primary-300">JU</Button>}
                                    </div>
                                    <div onClick={handleFriday}>
                                        {friday ? <Button type="button" className="p-1 w-8 rounded-full font-normal bg-neutro-200">VI</Button> : <Button type="button" className="p-1 w-8 rounded-full font-normal bg-primary-300">VI</Button>}
                                    </div>
                                    <div onClick={handleSaturday}>
                                        {saturday ? <Button type="button" className="p-1 w-8 rounded-full font-normal bg-neutro-200">SA</Button> : <Button type="button" className="p-1 w-8 rounded-full font-normal bg-primary-300">SA</Button>}
                                    </div>
                                    <div onClick={handleSunday}>
                                        {sunday ? <Button type="button" className="p-1 w-8 rounded-full font-normal bg-neutro-200">DO</Button> : <Button type="button" className="p-1 w-8 rounded-full font-normal bg-primary-300">DO</Button>}
                                    </div>
                                </div>
                                <Typography as="span" className="text-sm text-neutro-800 mb-3">Seleccione el rango de día laborales</Typography>
                            </div>
                            <div className="flex flex-col gap-3">
                                <CardTitle className="text-primary-500 font-bold mt-5">Contacto</CardTitle>
                                <FormField
                                    control={form.control}
                                    name="phone"
                                    render={({ field }) => (
                                        <>
                                            <FormItem>
                                            <FormLabel htmlFor='phone'>Número de contacto</FormLabel>
                                            <FormControl>
                                                <Input type="number" placeholder="+54 9545874548" className="border border-neutro-400"  {...field} required/>
                                            </FormControl>
                                            <Typography as="span" className="text-sm text-neutro-800 mb-1">Ingrese el número de contacto</Typography>
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
                                            <Typography as="span" className="text-sm text-neutro-800 mb-1">Ingrese el correo electrónico de la empresa</Typography>
                                            </FormItem>
                                        </>
                                    )}
                                />
                            </div>
                        </div>    
                        <div className="flex flex-col  justify-between">
                                <CardTitle className="text-primary-500 font-bold mt-5 md:mt-0">Ubicación</CardTitle>
                                <FormField
                                    control={form.control}
                                    name="country"
                                    render={({ field }) => (
                                        <>
                                            <FormItem>
                                            <FormLabel htmlFor='country'>País</FormLabel>
                                            <FormControl>
                                                <Input type="text" placeholder="Argentina" className="border border-neutro-400"  {...field} required/>
                                            </FormControl>
                                            <Typography as="span" className="text-sm text-neutro-800 md:mb-0">Ingrese el país donde la empresa ejerce sus actividades</Typography>
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
                                                <Input type="text" placeholder="Códoba" className="border border-neutro-400"  {...field} required/>
                                            </FormControl>
                                            <Typography as="span" className="text-sm text-neutro-800 md:mb-0 ">Ingrese la provincia donde se ubica la empresa</Typography>
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
                                            <Typography as="span" className="text-sm text-neutro-800 mb-1">Ingrese la ciudad donde se ubica la empresa</Typography>
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
                                            <FormLabel htmlFor='address'>Dirección</FormLabel>
                                            <FormControl>
                                                <Input type="text" placeholder="Av.Corrientes 1234" className="border border-neutro-400"  {...field} required/>
                                            </FormControl>
                                            <Typography as="span" className="text-sm text-neutro-800">Ingrese la dirección de la empresa</Typography>
                                            </FormItem>
                                        </>
                                    )}
                                />
                                <FormField
                                    control={form.control}
                                    name="zipcode"
                                    render={({ field }) => (
                                        <>
                                            <FormItem>
                                            <FormLabel htmlFor='zipCode'>Código postal</FormLabel>
                                            <FormControl>
                                                <Input type="text" placeholder="B5000" className="border border-neutro-400"  {...field} required/>
                                            </FormControl>
                                            <Typography as="span" className="text-sm text-neutro-800 mb-1">Ingrese el código postal del empleado</Typography>
                                            </FormItem>
                                        </>
                                    )}
                                />
                        </div>
                    </div>
                    <CardFooter className="p-0">
                        <Button 
                            onClick={handleFormClose}
                            disabled={isSubmitting}
                            variant='default'
                            type="submit" className="bg-primary-500 text-[#FFFFFF] hover:bg-primary-400 shadow-md w-full mt-9"
                        >
                            {isSubmitting ? <Loader /> : 'Aceptar'}
                        </Button>
                    </CardFooter>
                    {errors?.root?.server && <FormMessage>{errors?.root?.server.message}</FormMessage>}
                </form>
            </Form>
            </CardContent>
        </Card>
    )

}