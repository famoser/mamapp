import { displayError } from '@/services/notifiers'

const httpClient = {
  request: async function (url: string, init: RequestInit = {}): Promise<Response | null> {
    let response: Response

    if (window.location.hostname === 'localhost') {
      url = 'http://localhost:5173' + url
    }

    try {
      response = await fetch(url, init)
    } catch (error) {
      const err = error as Error
      if (err.name === 'AbortError') {
        // hide aborted errors (happens when navigating rapidly in firefox)
        return null
      }

      console.log(error)
      displayError('Failed: ' + String(error))

      throw error
    }

    if (!response.ok) {
      const errorText = response.status + ': ' + response.statusText
      const error = new Error(errorText)

      console.log(error)
      displayError('Failed with error ' + errorText)

      throw error
    }

    return response
  }
}

export const restClient = {
  get: async function (url: string, options: RequestInit = {}) {
    const response = await httpClient.request(url, options)
    return response?.json()
  }
}
