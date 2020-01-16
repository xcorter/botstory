class AnswerRepository {

    save(answerId: string, data: any) {
        return fetch('/admin/game/answer/' + answerId, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json;charset=utf-8'
            },
            body: JSON.stringify(data)
        })
    }

}

export default new AnswerRepository();