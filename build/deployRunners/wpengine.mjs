import { execSync } from 'child_process';

export default async ({ themeName, envName, preBuildTask = false }) => {
  if (!envName) {
    process.stdout.write(
      '\x1b[31mError:\x1b[0m Please specify the environment name.\n'
    )
    process.exit(1)
  }

  const sshUser = `${envName}`
  const sshServer = `${envName}.ssh.wpengine.net`
  const sshSitePath = `~/sites/${envName}`
  const sshThemePath = `${sshSitePath}/wp-content/themes/${themeName}`

  const tempDir = `${sshThemePath}-temp`
  const oldDir = `${sshThemePath}-old`
  const currentDir = `${sshThemePath}`

  const uploadFiles = `rsync -e 'ssh -p 22 -o StrictHostKeyChecking=no' -avz --delete --delete-excluded --include-from=rsync.include.lst ./ ${sshUser}@${sshServer}:${tempDir}`

  const renameDirs = `
ssh -t ${sshUser}@${sshServer} <<EOF
echo "Removing ${oldDir} if it exists..."
rm -rf ${oldDir}
echo "Renaming ${currentDir} to ${oldDir}..."
mv ${currentDir} ${oldDir}
echo "Renaming ${tempDir} to ${currentDir}..."
mv ${tempDir} ${currentDir}
echo "Flushing caches..."
cd ${sshSitePath}
wp cache flush
EOF
`

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
    {
      title: 'Renaming directories and flushing server caches',
      command: renameDirs,
    }
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
    process.stdout.write(String(error)) // Ensure error is a string
    process.exit(1)
  }

  return true;
}
