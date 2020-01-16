class QuestionRepository {

    save(questionId: string, data: any) {
        return fetch('/admin/game/question/' + questionId, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json;charset=utf-8'
            },
            body: JSON.stringify(data)
        })
    }

}

export default new QuestionRepository();