const consoleMsg = (msg, type = 'info') => {
  const msgColors = {
    info: ['#1ae', '#88d5f7'],
    success: ['#1ea', '#88f7d5'],
    warning: ['#ea1', '#f7d588'],
    error: ['#e1a', '#f788d5']
  }
  if (!msg) {
    return console.log(`\n%c %c${msg}`, `background-color:${msgColors['warning'][0]};padding:8px 1px;`, `background-color:${msgColors['warning'][1]};color:#111;display:block;font-size:12px;font-weight:bold;padding:8px;`)
  }
  return console.log(`\n%c %c${msg}`, `background-color:${msgColors[type][0]};padding:8px 1px;`, `background-color:${msgColors[type][1]};color:#111;display:block;font-size:12px;font-weight:bold;padding:8px;`)
}

const fillWithZero = (value, desiredLength = 2) => {
  if (!value) {
    consoleMsg('Ops... não identificamos o tempo restante atual', 'error')
  }
  const currentLength = value.length
  return currentLength < desiredLength
    ? '0'.repeat(desiredLength - currentLength) + value
    : value
}

const timeFormatter = (dateDiff, format = 'HMS', minDigits = 2) => {
  if (!dateDiff) {
    return consoleMsg('Ops... houve um erro no cálculo do tempo restante...', 'error')
  }
  switch (format) {
    case 'DHMS':
    case 'DHM':
    case 'DH':
    case 'D':
      return ({
        days: fillWithZero(Math.floor(dateDiff / 86400).toString(), minDigits),
        hours: fillWithZero(Math.floor((dateDiff % 86400) / 3600).toString(), minDigits),
        minutes: fillWithZero(Math.floor((dateDiff % 3600) / 60).toString(), minDigits),
        seconds: fillWithZero(Math.floor(dateDiff % 60).toString(), minDigits)
      })
    case 'HMS':
    case 'HM':
    case 'H':
      return ({
        hours: fillWithZero(Math.floor(dateDiff / 3600).toString(), minDigits),
        minutes: fillWithZero(Math.floor((dateDiff % 3600) / 60).toString(), minDigits),
        seconds: fillWithZero(Math.floor(dateDiff % 60).toString(), minDigits)
      })
    case 'MS':
    case 'M':
      return ({
        minutes: fillWithZero(Math.floor(dateDiff / 60).toString(), minDigits),
        seconds: fillWithZero(Math.floor(dateDiff % 60).toString(), minDigits)
      })
    case 'S':
      return ({
        seconds: fillWithZero(Math.floor(dateDiff).toString(), minDigits)
      })
    default:
      return ({
        days: fillWithZero(Math.floor(dateDiff / 86400).toString(), minDigits),
        hours: fillWithZero(Math.floor((dateDiff % 86400) / 3600).toString(), minDigits),
        minutes: fillWithZero(Math.floor((dateDiff % 3600) / 60).toString(), minDigits),
        seconds: fillWithZero(Math.floor(dateDiff % 60).toString(), minDigits)
      })
  }
}

const render = (counterContainer, remainingTime, timeFormat = 'HMS', minDigits = 2) => {
  if (counterContainer.children) {
    if (!remainingTime) {
      consoleMsg('Ops... houve algum problema com o cálculo do tempo restante...', 'error')
      return
    }
    for (let counterItem of counterContainer.children) {
      if (timeFormat.search('D') >= 0 && counterItem.dataset.unit === 'days')
        counterItem.innerText = fillWithZero(remainingTime.days, minDigits)
      if (timeFormat.search('H') >= 0 && counterItem.dataset.unit === 'hours')
        counterItem.innerText = fillWithZero(remainingTime.hours, minDigits)
      if (timeFormat.search('M') >= 0 && counterItem.dataset.unit === 'minutes')
        counterItem.innerText = fillWithZero(remainingTime.minutes, minDigits)
      if (timeFormat.search('S') >= 0 && counterItem.dataset.unit === 'seconds')
        counterItem.innerText = fillWithZero(remainingTime.seconds, minDigits)
    }
  } else {
    consoleMsg('Não encontramos elementos dentro do container com a classe counter', 'warning')
  }
  return
}

let count

const counterChecker = (counterContainer, targetDate, timeFormat = 'DHMS', minDigits = '2') => {
  if (!targetDate) {
    consoleMsg('Por favor, inclua o atributo \'data-time-target\' (DHMS) em cada counter.', 'error')
    clearInterval(count)
    return
  }
  const timeDiff = (new Date(targetDate) - new Date()) / 1000
  if (timeDiff <= 0) {
    render(counterContainer, timeFormatter(0, timeFormat, minDigits), timeFormat, minDigits)
    clearInterval(count)
    return
  }
  return render(counterContainer, timeFormatter(timeDiff, timeFormat, minDigits), timeFormat)
}

const initCounter = (counterContainer, minDigits = '2') => {
  let { targetDate, timeFormat } = counterContainer.dataset
  if (!timeFormat || timeFormat == '') timeFormat = 'DHMS'
  let timeInterval = 1000
  const minorTimeUnit = timeFormat ? timeFormat[timeFormat.length - 1] : 'S'
  switch (minorTimeUnit) {
    case 'D':
      timeInterval = 1000 * 60 * 60 * 24
      break
    case 'H':
      timeInterval = 1000 * 60 * 60
      break
    case 'M':
      timeInterval = 1000 * 60
      break
    default:
      timeInterval = 1000
  }
  if (counterContainer.children) {
    counterChecker(counterContainer, targetDate, timeFormat, minDigits)
    count = setInterval(() => counterChecker(counterContainer, targetDate, timeFormat, minDigits), timeInterval)
  } else {
    consoleMsg('Não encontramos elementos dentro do container com a classe counter', 'warning')
  }
  return
}

const preset = (counterContainer, minDigits = '2') => {
  if (counterContainer.children && counterContainer.children.length) {
    return initCounter(counterContainer, minDigits)
  } else {
    consoleMsg('Não encontramos elementos dentro do container com a classe counter', 'warning')
  }
}

const createCounterUnit = unit => {
  const counterUnit = document.createElement('span')
  counterUnit.setAttribute('class', 'counter__unit')
  switch (unit) {
    case 'D':
      counterUnit.setAttribute('data-unit', 'days')
      break
    case 'H':
      counterUnit.setAttribute('data-unit', 'hours')
      break
    case 'M':
      counterUnit.setAttribute('data-unit', 'minutes')
      break
    case 'S':
      counterUnit.setAttribute('data-unit', 'seconds')
      break
    default:
      console.log('Unit must be H, D, M or S')
      break
  }
  return counterUnit
}

const mountCounter = (targetDate, timeFormat = 'DHMS', minDigits = '2', lang = 'pt', theme = '') => {
  const counterContainer = document.createElement('div')
  theme == ''
    ? counterContainer.setAttribute('class', 'counter')
    : counterContainer.setAttribute('class', `counter counter--${theme}`)
  counterContainer.setAttribute('data-target-date', targetDate)
  counterContainer.setAttribute('data-time-format', timeFormat)
  counterContainer.setAttribute('data-min-digits', minDigits)
  counterContainer.setAttribute('data-lang', lang)
  for (let unit of timeFormat) {
    const counterUnit = createCounterUnit(unit)
    counterContainer.appendChild(counterUnit)
  }
  return counterContainer
}

const counter = () => {
  const countersElements = document.querySelectorAll('.counter')
  if (countersElements.length) {
    for (let counterElement of countersElements) {
      const minDigits = counterElement.dataset.minDigits || '2'
      preset(counterElement, minDigits)
    }
  } else {
    return consoleMsg('Por favor, declare ao menos uma tag HTML com a classe counter.', 'error')
  }
}

const countdown = { counter, mountCounter }

export default countdown
export { counter, mountCounter }