import Pusher from 'pusher-js'
import * as PusherTypes from 'pusher-js'
import { useEffect, useState } from 'react'

function useBroadcastNewVacation() {
	const [vacation, setVacation] = useState<number>(0)

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
				alert(JSON.stringify(vacation + 1))
				setVacation(vacation + 1)
			})

			return () => {
				pusher.unsubscribe('new-vacation')
			}
		}
	}, [])

	return [vacation, setVacation]
}

export default useBroadcastNewVacation
