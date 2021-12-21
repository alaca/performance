/**
 * @typedef WPPluginCLIConfig
 *
 * @property {string} githubRepositoryOwner GitHub Repository Owner.
 * @property {string} githubRepositoryName  GitHub Repository Name.
 */

/**
 * @type {WPPluginCLIConfig}
 */
const config = {
	githubRepositoryOwner: 'WordPress',
	githubRepositoryName: 'performance',
	textdomain: 'performance-lab',
};

module.exports = config;
