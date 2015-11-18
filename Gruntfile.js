module.exports = function(grunt) {
	grunt.loadNpmTasks('grunt-ftpuploadtask');

	grunt.initConfig({
		pkg: grunt.file.readJSON('package.json'),

		ftpUploadTask: {
			content_tunapanda_org: {
				options: {
					user: "contentdeploy",
					password: process.env.CONTENT_TUNAPANDA_PASSWORD,
					host: "thespeakeasytimes.com",
					checksumfile: "_upload_checksum_file.json"
				},

				files: [{
					expand: true,
					dest: "wp-content/themes/content_tunapanda_org",
					src: ["**","!node_modules/**"]
				}]
			},
		}
	});
}