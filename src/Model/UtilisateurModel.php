<?php
namespace App\Model;

use App\Service\PdoService;

class UtilisateurModel
{
    private \PDO $pdo;

    /** Constructor
     *
     * @param PdoService $pdoService The object responsible of the connection with the database
     */
    public function __construct(PdoService $pdoService)
    {
        $this->pdo = $pdoService->getPdo();
    }

    /** Get all users
     *
     * @return array The list of all users
     */
    public function getAllUsers(): array
    {
        $requete = $this->pdo->query("SELECT * FROM Utilisateurs");
        return $requete->fetchAll();
    }

    /** Get a user by their email
     *
     * @param string $email The email of the user
     * @return array|false The user if found, false otherwise
     */
    public function getUserByEmail(string $email): array|false
    {
        $requete = $this->pdo->prepare("SELECT * FROM Utilisateurs WHERE email = :email");
        $requete->execute(['email' => $email]);
        return $requete->fetch();
    }

    /** Get a user by their ID
     *
     * @param int $id The ID of the user
     * @return array|false The user if found, false otherwise
     */
    public function getUserById(int $id): array|false
    {
        $requete = $this->pdo->prepare("SELECT * FROM Utilisateurs WHERE Id_utilisateur = :id");
        $requete->execute(['id' => $id]);
        return $requete->fetch();
    }

    /** Add a new user
     *
     * @param string $name The last name of the user
     * @param string $firstname The first name of the user
     * @param string $gender The gender of the user
     * @param string $email The email of the user
     * @param string $phone The phone number of the user
     * @param $school The school of the user, can be null
     * @param string $password The password of the user
     * @param string $school The school of the user
     * @param int $id_city The ID of the city of the user
     * @param int $id_type_account The ID of the type of account of the user
     * 
     * @return bool True if the insertion was successful, false otherwise
     */
    public function addUser(string $name,string $firstname, string $gender, string $email, string $phone, string $password, $school ,int $id_city, int $id_account): bool
    {
        $requete = $this->pdo->prepare("INSERT INTO Utilisateurs (Nom, Prenom, Genre, Email, Telephone, Mdp, Ecole, Id_ville, Id_type_compte) VALUES (:nom, :prenom, :genre, :email, :telephone, :password, :ecole, :id_ville, :id_account)");
        return $requete->execute(['nom' => $name, 'prenom' => $firstname, 'genre' => $gender, 'email' => $email, 'telephone' => $phone, 'password' => $password, 'ecole' => $school, 'id_ville' => $id_city, 'id_account' => $id_account]);
    }

    /** Update an existing user
     *
     * @param int $id The ID of the user to update
     * @param string $name The new last name of the user
     * @param string $firstname The new first name of the user
     * @param string $gender The new gender of the user
     * @param string $email The new email of the user
     * @param string $phone The new phone number of the user
     * @param string $school The new school of the user
     * @param string $password The new password of the user
     * @param int $id_city The new ID of the city of the user
     * @param int $id_type_account The new ID of the type of account of the user
     * 
     * @return bool True if the update was successful, false otherwise
     */
    public function updateUser(int $id, string $name, string $firstname, string $gender, string $email, string $phone, string $school, string $password, int $id_city, int $id_type_account): bool
    {
        $requete = $this->pdo->prepare("UPDATE Utilisateurs SET Nom = :nom, Prenom = :prenom, Genre = :genre, Email = :email, Telephone = :telephone, Ecole = :ecole, Mdp = :password, Id_ville = :id_ville, Id_type_compte = :id_type_compte WHERE Id_utilisateur = :id");
        return $requete->execute(['nom' => $name, 'prenom' => $firstname, 'genre' => $gender, 'email' => $email, 'telephone' => $phone, 'ecole' => $school, 'password' => $password, 'id_ville' => $id_city, 'id_type_compte' => $id_type_account, 'id' => $id]);
    }

    public function updateUserPassword(int $id, string $password): bool
    {
        $requete = $this->pdo->prepare("UPDATE Utilisateurs SET Mdp = :password WHERE Id_utilisateur = :id");
        return $requete->execute(['password' => $password, 'id' => $id]);
    }

    public function updateUserSchool(int $id, string $school): bool
    {
        $requete = $this->pdo->prepare("UPDATE Utilisateurs SET Ecole = :school WHERE Id_utilisateur = :id");
        return $requete->execute(['school' => $school, 'id' => $id]);
    }

    public function updateUserEmail(int $id, string $email): bool
    {
        $requete = $this->pdo->prepare("UPDATE Utilisateurs SET Email = :email WHERE Id_utilisateur = :id");
        return $requete->execute(['email' => $email, 'id' => $id]);
    }

    public function updateUserPhone(int $id, string $phone): bool
    {
        $requete = $this->pdo->prepare("UPDATE Utilisateurs SET Telephone = :phone WHERE Id_utilisateur = :id");
        return $requete->execute(['phone' => $phone, 'id' => $id]);
    }

    public function updateUserCity(int $id, int $id_city): bool
    {
        $requete = $this->pdo->prepare("UPDATE Utilisateurs SET Id_ville = :id_city WHERE Id_utilisateur = :id");
        return $requete->execute(['id_city' => $id_city, 'id' => $id]);
    }

    /** Delete a user
     *
     * @param int $id The ID of the user to delete
     * @return bool True if the deletion was successful, false otherwise
     */
    public function deleteUser(int $id): bool
    {
        $requete = $this->pdo->prepare("DELETE FROM Utilisateurs WHERE Id_utilisateur = :id");
        return $requete->execute(['id' => $id]);
    }
}