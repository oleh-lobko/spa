import { execSync } from 'child_process';
import { createRequire } from 'module';
import path from 'path';

const require = createRequire(import.meta.url)

// Register ts-node to handle TypeScript files
require('ts-node').register()

export default async ({ themeName, username, password, preBuildTask = false, ignoreList = []}) => {
  if (!username) {
    process.stdout.write(
      '\x1b[31mError:\x1b[0m Please specify the username.\n'
    )
    process.exit(1)
  }

  if (!password) {
    process.stdout.write(
      '\x1b[31mError:\x1b[0m Please specify the password.\n'
    )
    process.exit(1)
  }

  if (preBuildTask) {
    const preBuildCommand = typeof preBuildTask === 'string' ? `yarn run ${preBuildTask}` : 'yarn run build';
    try {
      process.stdout.write(`\x1b[34mBuilding package...\x1b[0m\n`)
      execSync(preBuildCommand, { stdio: 'inherit' })
      process.stdout.write(`\x1b[32mSuccess!\x1b[0m\n`)
    } catch (e) {
      process.stdout.write(`\x1b[31mPre-build failed:\x1b[0m ${e.message}\n`)
      process.exit(1)
    }
  }

  const sftpConfig = {
    host: 'sftp.pressable.com',
    port: 22,
    username,
    password
  }
  const themePath = `/srv/htdocs/wp-content/themes/${themeName}`

  const tempDir = `${themePath}-temp`
  const oldDir = `${themePath}-old`
  const currentDir = `${themePath}`

  // Deploy the theme to Pressable via SFTP
  const Client = require('ssh2-sftp-client')
  let sftp = new Client('pressable');

  const srcDir = path.join(process.cwd());

  try {
    process.stdout.write(`\x1b[34mConnecting to SFTP server...\x1b[0m\n`)
    await sftp.connect(sftpConfig).then(() => {
      process.stdout.write(`\x1b[32mSuccess!\x1b[0m\n`)
    });

    sftp.on('upload', info => {
      process.stdout.write(`${path.relative(srcDir, info.source)}\n`)
    });

    process.stdout.write(`\x1b[34mUploading files to the temp directory...\x1b[0m\n`)
    await sftp.uploadDir(srcDir, tempDir, {
      useFastput: true,
      filter: (fullPath) => !ignoreList.includes(path.relative(srcDir, fullPath))
    }).then(() => {
      process.stdout.write(`\x1b[32mSuccess!\x1b[0m\n`)
    });

    process.stdout.write(`\x1b[34mRemoving old directory...\x1b[0m\n`)
    await sftp.rmdir(oldDir, true).then(() => {
      process.stdout.write(`\x1b[32mSuccess!\x1b[0m\n`)
    });

    process.stdout.write(`\x1b[34mRenaming current directory to old directory...\x1b[0m\n`)
    await sftp.rename(currentDir, oldDir).then(() => {
      process.stdout.write(`\x1b[32mSuccess!\x1b[0m\n`)
    });

    process.stdout.write(`\x1b[34mRenaming temp directory to current directory...\x1b[0m\n`)
    await sftp.rename(tempDir, currentDir).then(() => {
      process.stdout.write(`\x1b[32mSuccess!\x1b[0m\n`)
    });
  } catch (e) {
    process.stdout.write(`\x1b[31mError:\x1b[0m ${e.message}\n`)
    process.exit(1)
  } finally {
    sftp.end();
    process.stdout.write(`\x1b[34mConnection closed.\x1b[0m\n`)
  }

  return true;
};
