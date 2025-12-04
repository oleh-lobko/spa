import { createRequire } from 'module';
import path from 'path';
import { performance } from 'perf_hooks';

const require = createRequire(import.meta.url)
const config = require('./config')
const { themeName = 'fwp' } = config

// Parse arguments
const processArgs = (() => {
  const allowedArgs = [
    {
      name: '--target',
      description: 'Specify the target name.',
      required: true,
    },
    {
      name: '--skipPreBuild',
      description: 'Skip the pre-build task.',
      isBoolean: true,
    },
  ]

  const filteredArgs = process.argv.filter((arg) => arg.startsWith('--')).map((arg) => arg.split('='))

  const parsedArgs = {}
  allowedArgs.forEach(({ name, required = false, description = '', isBoolean = false, defaultValue = false }) => {
    const foundArg = filteredArgs.find((arg) => arg[0] === name)
    if (required && !foundArg) {
      process.stdout.write(
        `\x1b[31mError: \x1b[0mFlag "${name}" is required.\n - ${description}\n`
      )
      process.exit(1)
    }

    let argValue = foundArg && foundArg[1] !== undefined ? foundArg[1] : defaultValue
    if (isBoolean) {
      argValue = !!foundArg
    }

    parsedArgs[name.replace(/^--/, '')] = argValue
  })

  return {
    ...parsedArgs,
    get: (key) => parsedArgs[key] ?? false,
  }
})()

// Load the target config
const target = processArgs.get('target')
const targetConfig = require(path.resolve(`${process.cwd()}/deploy_targets/${target}.json`))
if (!targetConfig) {
  process.stdout.write(
    `\x1b[31mError: \x1b[0m Target "${target}" not found.\n`
  )
  process.exit(1)
}
if (!targetConfig.runner) {
  process.stdout.write(
    `\x1b[31mError: \x1b[0m Runner not specified for "${target}".\n`
  )
  process.exit(1)
}

const startTime = performance.now();
// Load the runner
let runner = null
try {
  runner = await import(`./deployRunners/${targetConfig.runner}.mjs`).then(module => module.default)
} catch (e) {
  process.stdout.write(
    `\x1b[31mError: \x1b[0m Runner "${targetConfig.runner}" not found.\n`
  )
  process.exit(1)
}

// Run the deployment
try {
  await runner({
    themeName,
    ...targetConfig,
    preBuildTask: !processArgs.get('skipPreBuild') && (targetConfig?.preBuildTask ?? false),
  });
  const endTime = performance.now();
  const timeTaken = ((endTime - startTime) / 1000).toFixed(2);
  process.stdout.write(
    `\x1b[32mSuccess: \x1b[0mDeployment to "${target}" completed in ${timeTaken} seconds.\n`
  )
} catch (e) {
  const endTime = performance.now();
  const timeTaken = ((endTime - startTime) / 1000).toFixed(2);
  process.stdout.write(
    `\x1b[31mError: \x1b[0mRunner "${targetConfig.runner}" failed after ${timeTaken} seconds.\n`
  )
  if (e.message) {
    process.stdout.write(
      `\x1b[31m -\x1b[0m${e.message} \n`
    )
  } else {
    process.stdout.write(
      `\x1b[31m -\x1b[0mDetails: ${JSON.stringify(e)} \n`
    );
  }
  process.exit(1)
}
