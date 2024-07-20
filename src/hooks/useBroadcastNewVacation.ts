'use client'

import Pusher from 'pusher-js'

/* 
import * as PusherTypes from 'pusher-js' */
import { useEffect, useState } from 'react'

function useBroadcastNewVacation() {
	const [vacation, setVacation] = useState<string>('')

	useEffect(() => {
		const user = JSON.parse(localStorage.getItem('user') || '{}')
		if (user?.type === 'company') {
			console.log('registrandose')
			const userId = user.id

			const pusher = new Pusher('30dc5d26e21ba3445b18', {
				cluster: 'us2'
			})

			const channel = pusher.subscribe('new-vacation')
			channel.bind(userId, function (data: string) {
				/* 
				alert(JSON.stringify(data)) */
				setVacation(data)
			})

			return () => {
				pusher.unsubscribe('new-vacation')
			}
		}
	}, [])

	return [vacation, setVacation]
}

export default useBroadcastNewVacation
