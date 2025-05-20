Project: DragonX

Personal CRM project
Security Configuration for NGINX Server:

    # Block access to .php and .cache.php files in /cache
    location ~* ^/cache/.*\.php$ {
        deny all;
    }

    location ~* ^/cache/.*\.cache\.php$ {
        deny all;
    }

    # Only allow .css and .js files in /cache
    location ~* ^/cache/.*\.(css|js)$ {
        allow all;
        access_log off;
        add_header Cache-Control "max-age=604800, public";
    }

    # Deny access to everything else in /cache
    location /cache/ {
        autoindex off;
        deny all;
    }



Security Configuration for Windows IIS Server:

On Windows IIS, the configuration is handled through the IIS Manager or by editing the web.config file directly. Below is an equivalent security configuration for IIS:

    Open or create the web.config file in the root of your project (where index.php is located).

    Add the following security rules inside the <configuration> section of the web.config:


    <configuration>
        <system.webServer>
            <security>
                <!-- Block access to PHP and .cache.php files in /cache -->
                <urlCompression doDynamicCompression="false" />
                <staticContent>
                    <remove fileExtension=".php" />
                    <remove fileExtension=".cache.php" />
                </staticContent>
            </security>

            <!-- Block access to everything in /cache except .css and .js -->
            <rewrite>
                <rules>
                    <rule name="Block PHP and Cache PHP in /cache" stopProcessing="true">
                        <match url="^cache/.*\.php$" />
                        <action type="Rewrite" url="403" />
                    </rule>

                    <rule name="Block Cache .cache.php files" stopProcessing="true">
                        <match url="^cache/.*\.cache\.php$" />
                        <action type="Rewrite" url="403" />
                    </rule>

                    <rule name="Allow CSS and JS in /cache" stopProcessing="true">
                        <match url="^cache/.*\.(css|js)$" />
                        <action type="None" />
                    </rule>

                    <!-- Block everything else in /cache -->
                    <rule name="Deny everything else in /cache" stopProcessing="true">
                        <match url="^cache/" />
                        <action type="Rewrite" url="403" />
                    </rule>
                </rules>
            </rewrite>
        </system.webServer>
    </configuration>


Explanation:

    Block access to .php and .cache.php files: These rules ensure that sensitive PHP files and any cache files with .cache.php are blocked from direct access.

    Allow .css and .js files: These files are allowed to be publicly accessed in the /cache directory, ensuring they are accessible for styling and scripts.

    Block everything else: Any other files within /cache that don't match .css or .js extensions are blocked.

Conclusion:

    For NGINX, you need to modify the server's configuration file (nginx.conf or site.conf) to prevent unauthorized access to certain files in the /cache directory.

    For Windows IIS, the configuration is handled using the web.config file, where URL rewrite rules are defined to secure the /cache directory.

    For Apache, the .htaccess file provides granular control over access to files and directories, ensuring that only authorized file types can be accessed.

Make sure to restart your server after making these changes to apply the new security rules.