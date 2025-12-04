import { execSync } from 'child_process';

export default async ({ themeName, username, preBuildTask = false }) => {
  if (!username) {
    process.stdout.write(
      '\x1b[31mError:\x1b[0m Please specify the username.\n'
    )
    process.exit(1)
  }

  const sshUser = `${username}`
  const sshServer = `ssh.pressable.com`
  const sshSitePath = `~/htdocs/`
  const sshThemePath = `${sshSitePath}/wp-content/themes/${themeName}`

  const uploadFiles = `rsync -e 'ssh -p 22 -o StrictHostKeyChecking=no' -avz --delete --delete-excluded --include-from=rsync.include.lst ./ ${sshUser}@${sshServer}:${sshThemePath}`

  const steps = []

  if (preBuildTask) {
    steps.push({
      title: 'Building package',
      command:
        typeof preBuildTask === 'string' ? `yarn run ${preBuildTask}` : 'yarn run build',
    })
  }

  steps.push(
    {
      title: 'Uploading new files',
      command: uploadFiles,
    },
  )

  try {
    steps.forEach((step, index) => {
      process.stdout.write(
        `\x1b[34mStep ${index + 1}/${steps.length}: ${step.title}\n\x1b[0m`
      )
      execSync(step.command, { stdio: 'inherit' })
    })
  } catch (error) {
    process.stdout.write('\x1b[31mDeployment failed.\n\x1b[0m')
    process.stdout.write(String(error))
    process.exit(1)
  }

  return true;
}
